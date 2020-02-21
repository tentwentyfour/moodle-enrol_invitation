<?php

namespace enrol_invitation\event;

defined('MOODLE_INTERNAL') || die();

class invitation_view extends \core\event\base
{
 protected function init()
    {
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'enrol_invitation';
    }

    public static function get_name()
    {
        return get_string('eventInvitationExpired', 'enrol_invitation');
    }

    public function get_description()
    {
        return "The user with id {$this->userid} tried to accept an expired invitation with id {$this->objectid}.";
    }

    public function get_url()
    {
        return new \moodle_url('../enrol/invitation/history.php', ['courseid' => $this->objectid]);
    }

    public function get_legacy_logdata()
    {
        // Override if you are migrating an add_to_log() call.
        return [
            $this->courseid,
            'course',
            'invitation expired',
            $this->objectid,
            $this->context
        ];
    }
}