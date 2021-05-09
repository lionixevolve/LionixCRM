<?php

class LXAccountsBeforeAndAfterSaveMethods
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
        if (!empty(self::$fetchedRow[$bean->id])) {
            // execute changed record business process
            if ($bean->sales_stage != self::$fetchedRow[$bean->id]['sales_stage']) {
                $query = "
                    select 'setExampleWithPreviousDataValidationAS'
                    # update accounts
                    # set some_custom_date_field_c = utc_timestamp()
                    # where sales_stage = '{$bean->sales_stage}'
                    # and id = '{$bean->id}'
                ";
                $bean->db->query($query);
            }
        }
        // call on new records only
        if (empty(self::$fetchedRow[$bean->id])) {
            // execute new record business process
            if ($bean->sales_stage == 'some-custom-stage') {
                $query = "
                    select 'setExampleWithPreviousDataValidationAS'
                    # update accounts
                    # set some_custom_date_field_c = utc_timestamp()
                    # where sales_stage = '{$bean->sales_stage}'
                    # and id = '{$bean->id}'
                ";
                $bean->db->query($query);
            }
        }
    }

    public function setMissingNotesLinksAS(&$bean, $event, $arguments)
    {
        global $sugar_config;
        $files_fields = $sugar_config['lionixcrm']['modules']['accounts']['upload_files_fields'];
        foreach ($files_fields as $ff) {
            $sql = "
                UPDATE accounts a
                LEFT JOIN accounts_cstm ac ON a.id = ac.id_c
                LEFT JOIN notes n ON ac.{$ff['field_name']} LIKE CONCAT('%',n.id,'%')
                SET n.parent_id = a.id
                WHERE a.deleted = 0
                AND n.deleted = 0
                AND n.parent_type = 'accounts'
                AND n.parent_id = ''
                AND a.id = '{$bean->id}'
        ";
            $bean->db->query($sql);
        }
    }
}
