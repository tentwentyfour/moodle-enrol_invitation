<?php
// This file is part of the UCLA Site Invitation Plugin for Moodle - http://moodle.org/
// Modified by Dorset Creative to allow user signup and enrollment in one process
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This page will try to enrol the user.
 *
 * @package    enrol_invitation
 * @copyright  2013 UC Regents
 * @copyright  2011 Jerome Mouneyrac {@link http://www.moodleitandme.com}
 * @copyright  2020 Dorset Creative {@link https://www.dorsetcreative.co.uk}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require($CFG->dirroot . '/enrol/invitation/locallib.php');

//get the additional config settings for this plugin
$pluginConfig = get_config('enrol_invitation');

// Check if param token exist and bomb if not.
$enrolinvitationtoken = required_param('token', PARAM_ALPHANUM);

// Retrieve the token info.
$invitation = $DB->get_record('enrol_invitation',
    ['token' => $enrolinvitationtoken, 'tokenused' => false]);

// If token is valid, enrol the user into the course.
// check for validity of token/course
if (empty($invitation) or empty($invitation->courseid) or $invitation->timeexpiration < time()) {

    $courseid = empty($invitation->courseid) ? $SITE->id : $invitation->courseid;

    $event = \enrol_invitation\event\invitation_expired::create([
        'objectid' => $DB->get_record('course', ['id' => $courseid], 'fullname')->fullname
    ]);

    throw new moodle_exception('expiredtoken', 'enrol_invitation');
}

// Make sure that course exists.
$course = $DB->get_record('course', ['id' => $invitation->courseid], '*', MUST_EXIST);
$context = context_course::instance($course->id);

// Set up page.
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/enrol/invitation/enrol.php',
    ['token' => $enrolinvitationtoken]));
$PAGE->set_pagelayout('course');
$PAGE->set_course($course);
$pagetitle = get_string('invitation_acceptance_title', 'enrol_invitation');
$PAGE->set_heading($pagetitle);
$PAGE->set_title($pagetitle);
$PAGE->navbar->add($pagetitle);

// Get the invitation config
$invitationmanager = new invitation_manager($invitation->courseid);
$instance = $invitationmanager->get_invitation_instance($invitation->courseid);

// Have invitee confirm their acceptance of the site invitation.
$confirm = optional_param('confirm', 0, PARAM_BOOL);

//now see if they have entered a password
$password = optional_param('password', '', PARAM_RAW_TRIMMED);
$passwordConfirm = optional_param('password_confirm', '', PARAM_RAW_TRIMMED);

if (empty($confirm) || empty($password) || empty($passwordConfirm)) {

    //loading the page with no params so show the form
    require_once(dirname(__FILE__) . '/enrol_form.php');

    echo $OUTPUT->header();

    // Print out a heading.
    echo $OUTPUT->heading($pagetitle, 2, 'headingblock');

    $event = \enrol_invitation\event\invitation_view::create([
        'objectid' => $course->fullname,
        'context' => $context
    ]);

    // If invitation has "daysexpire" set, then give notice.
    if (!empty($invitation->daysexpire)) {
        $invitationacceptance .= html_writer::tag('p',
            get_string('daysexpire_notice', 'enrol_invitation',
                $invitation->daysexpire));
    }

    //show the password form
    $mform = new enrol_form(null, ['invitation' => $invitation], 'post');
    echo $mform->display();

    echo $OUTPUT->footer();
    exit;

} elseif (($confirm == 1) && ((empty($password) || empty($passwordConfirm)) || ($password != $passwordConfirm))) {

    //tried to submit with partial/no password or passwords don't match
    $event = \enrol_invitation\event\user_password_fail::create([
        'objectid' => $course->fullname,
        'context' => $context
    ]);

    redirect(new moodle_url('/enrol/invitation/enrol.php', ['token' => $invitation->token]), get_string('missing_password', 'enrol_invitation'), null, \core\output\notification::NOTIFY_ERROR);
    exit;

} else {
    //successful submission
    if ($USER->id == 0) {

        //user not logged in so try to find an account
        $invitationUser = $DB->get_record('user', ['email' => $invitation->email]);

        if (!$invitationUser) {
            //are we allowed to create an account?
            if($pluginConfig->createaccount == 0) {
                //create a new user
                require_once($CFG->dirroot . '/user/lib.php');
                $newUser = new stdClass();
                $newUser->email = $invitation->email;
                $newUser->username = $invitation->email; //substr($invitation->email, 0, strpos($invitation->email,'@'));
                $newUser->password = $password;
                $newUser->confirmed = 1; //force the confirm

                $newUser->id = user_create_user($newUser, true, true);

                if (!$newUser->id) {
                    throw new moodle_exception('couldnotcreateuser', 'enrol_invitation');
                } else {
                    $USER = $newUser;

                    $event = \enrol_invitation\event\create_user_from_invitation::create([
                        'objectid' => $course->fullname,
                        'context' => $context
                    ]);

                }
            } else {
                //can't create a user and one wasn't found
                redirect(new moodle_url('/enrol/invitation/enrol.php', ['token' => $invitation->token]), get_string('user_not_found_and_cant_create', 'enrol_invitation'), null, \core\output\notification::NOTIFY_ERROR);
                exit;
            }
        } else {
            //use existing user and ignore password
            $USER = $invitationUser;
        }
    }

    // User confirmed, so add them to the course.
    require_once($CFG->dirroot . '/enrol/invitation/locallib.php');
    $invitationmanager = new invitation_manager($invitation->courseid);
    $invitationmanager->enroluser($invitation);

    $event = \enrol_invitation\event\invitation_claim::create([
        'objectid' => $course->fullname,
        'context' => $context
    ]);

    // Set token as used and mark which user was assigned the token.
    $invitation->tokenused = true;
    $invitation->timeused = time();
    $invitation->userid = $USER->id;
    $DB->update_record('enrol_invitation', $invitation);

    if (!empty($invitation->notify_inviter)) {
        // Send an email to the user who sent the invitation.
        $inviter = $DB->get_record('user', ['id' => $invitation->inviterid]);

        $contactuser = new object;
        $contactuser->email = $inviter->email;
        $contactuser->firstname = $inviter->firstname;
        $contactuser->lastname = $inviter->lastname;
        $contactuser->maildisplay = true;

        $emailinfo = prepare_notice_object($invitation);
        $emailinfo->userfullname = trim($USER->firstname . ' ' . $USER->lastname);
        $emailinfo->useremail = $USER->email;
        $courseenrolledusersurl = new moodle_url('/enrol/users.php',
            ['id' => $invitation->courseid]);
        $emailinfo->courseenrolledusersurl = $courseenrolledusersurl->out(false);
        $invitehistoryurl = new moodle_url('/enrol/invitation/history.php',
            ['courseid' => $invitation->courseid]);
        $emailinfo->invitehistoryurl = $invitehistoryurl->out(false);

        $course = $DB->get_record('course', ['id' => $invitation->courseid]);
        $emailinfo->coursefullname = sprintf('%s: %s', $course->shortname, $course->fullname);
        $emailinfo->sitename = $SITE->fullname;
        $siteurl = new moodle_url('/');
        $emailinfo->siteurl = $siteurl->out(false);

        email_to_user($contactuser, get_admin(),
            get_string('emailtitleuserenrolled', 'enrol_invitation', $emailinfo),
            get_string('emailmessageuserenrolled', 'enrol_invitation', $emailinfo));

    }

    \core\notification::success(get_string('enrollment_complete', 'enrol_invitation', prepare_notice_object($invitation)));

    $courseurl = new moodle_url('/course/view.php', ['id' => $invitation->courseid]);
    redirect($courseurl);
}
