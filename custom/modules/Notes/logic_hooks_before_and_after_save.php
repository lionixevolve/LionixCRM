<?php

class LXNotesBeforeAndAfterSaveMethods
{
    // This static array moves between both logics hooks
    protected static $fetchedRow = array();

    //before_save methods
    public function saveFetchedRowBS(&$bean, $event, $arguments)
    {
        if (!empty($bean->id)) {
            self::$fetchedRow[$bean->id] = $bean->fetched_row;
        }
    }

    public function setContactIdWhenRelatedToIsContactsBS(&$bean, $event, $arguments)
    {
        if ($bean->parent_type=='Contacts' && !empty($bean->parent_id) && empty($bean->contact_id)) {
            $bean->contact_id = $bean->parent_id;
            $bean->parent_type = 'Accounts';
            $bean->parent_id = '';
        }
    }

    //after_save methods
}
