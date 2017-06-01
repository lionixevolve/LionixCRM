<?php

class LXUsersBeforeLogOutMethods
{
    public function setNewTrackerRecord(&$bean, $event, $arguments)
    {
        $user_id = '8673ab83-3bc1-795e-0a23-592f32003f67';
        $user_name = 'cumana';
        $session_id = 'xxx';
        $query = "
        INSERT INTO tracker
        (monitor_id, user_id, module_name, item_id, item_summary, date_modified, `action`, session_id, visible, deleted)
        VALUES('logout-logic-hook', '{$user_id}', 'Logout', '{$user_id}', 'Log Out performed by {$user_name}', utc_timestamp(), 'login', '$session_id', 0, 0);
        ";
        $bean->db->query($query);
    }
}
