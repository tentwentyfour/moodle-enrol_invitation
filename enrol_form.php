<?php
// This file is part of the Dorset Creative extension of the UCLA Site Invitation Plugin for Moodle - http://moodle.org/
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
 * Form to display invitation.
 *
 * @package    enrol_invitation
 * @copyright  2013 UC Regents
 * @copyright  2020 Dorset Creative
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    //  It must be included from a Moodle page.
}

require_once('locallib.php');
require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/lib/enrollib.php');

class enrol_form extends moodleform
{

    function definition()
    {
        global $CFG;
        $mform = &$this->_form;

        // Get rid of "Collapse all" in Moodle 2.5+.
        if (method_exists($mform, 'setDisableShortforms')) {
            $mform->setDisableShortforms(true);
        }

        $invitation = $this->_customdata['invitation'];

        $mform->addElement('hidden', 'token');
        $mform->setType('token', PARAM_ALPHANUM);
        $mform->setDefault('token', $invitation->token);

        $mform->addElement('hidden', 'confirm');
        $mform->setType('confirm', PARAM_BOOL);
        $mform->setDefault('confirm', 1);

        \core\notification::info(get_string('invitationacceptance', 'enrol_invitation', prepare_notice_object($invitation)));

        $mform->addElement('html', get_string('password_prompt', 'enrol_invitation'));

        $mform->addElement('password', 'password', get_string('user_password', 'enrol_invitation'));
        $mform->addRule('password', null, 'required', null, 'client');

        $mform->addElement('password', 'password_confirm', get_string('user_password_confirm', 'enrol_invitation'), ['required' => true]);
        $mform->addRule('password_confirm', null, 'required', null, 'client');

        if (!empty($CFG->passwordpolicy)) {
            $mform->addElement('static', 'passwordpolicyinfo', '', print_password_policy());
        }

        $this->add_action_buttons(false, get_string('invitationacceptancebutton', 'enrol_invitation'));
    }
}