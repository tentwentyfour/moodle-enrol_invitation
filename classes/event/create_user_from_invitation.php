<?php


namespace enrol_invitation\event;


class create_user_from_invitation extends \core\event\base
{
    protected function init()
    {
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'enrol_invitation';
    }

    public static function get_name()
    {
        return get_string('eventCreateUserFromInvitation', 'enrol_invitation');
    }

    public function get_description()
    {
        return "The user with id {$this->userid} was created from invitation with id {$this->objectid}.";
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
            'invitation claimed',
            $this->objectid,
            $this->context
        ];
    }
}