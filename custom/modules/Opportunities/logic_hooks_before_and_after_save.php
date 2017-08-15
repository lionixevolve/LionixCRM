<?php

class LXOpportunitiesBeforeAndAfterSaveMethods
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

    //after_save methods
    public function setExampleWithPreviousDataValidationAS(&$bean, $event, $arguments)
    {
        // call on changed records only
        if (isset(self::$fetchedRow[$bean->id])) {
            // execute changed record business process
            if ($bean->sales_stage != self::$fetchedRow[$bean->id]['sales_stage']) {
                $query = "
                    select 'setExampleWithPreviousDataValidationAS'
                    # update opportunities
                    # set some_custom_date_field_c = utc_timestamp()
                    # where sales_stage = '{$bean->sales_stage}'
                    # and id = '{$bean->id}'
                ";
                $bean->db->query($query);
            }
        }
        // call on new records only
        if (!isset(self::$fetchedRow[$bean->id])) {
            // execute new record business process
            if ($bean->sales_stage == 'some-custom-stage') {
                $query = "
                    select 'setExampleWithPreviousDataValidationAS'
                    # update opportunities
                    # set some_custom_date_field_c = utc_timestamp()
                    # where sales_stage = '{$bean->sales_stage}'
                    # and id = '{$bean->id}'
                ";
                $bean->db->query($query);
            }
        }
    }

    public function setMainContactCAS(&$bean, $event, $arguments)
    {
        $bean->custom_fields->retrieve();
        $query = "
            select count(1)
            from opportunities_contacts oc
            where oc.deleted = 0
            and oc.opportunity_id = '{$bean->id}'
            and oc.contact_id = '{$bean->maincontact_c}'
        ";
        $qty = $bean->db->getOne($query);
        if (empty($qty)) {
            $query = "
                insert into opportunities_contacts (id,contact_id,opportunity_id,date_modified,deleted)
                values (uuid(),'{$bean->maincontact_c}','{$bean->id}',utc_timestamp(),0)
            ";
            $bean->db->query($query);
        }
    }
}
