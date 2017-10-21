<?php

class LXContactsBeforeAndAfterSaveMethods
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
                    # update contacts
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
                    # update contacts
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
        $files_fields = $sugar_config['lionixcrm']['modules']['contacts']['upload_files_fields'];
        foreach ($files_fields as $ff) {
            $sql = "
                UPDATE contacts c
                LEFT JOIN contacts_cstm cc ON c.id = cc.id_c
                LEFT JOIN notes n ON cc.{$ff['field_name']} LIKE CONCAT('%',n.id,'%')
                SET n.contact_id = c.id
                WHERE c.deleted = 0
                AND n.deleted = 0
                AND c.id = '{$bean->id}'
        ";
            $bean->db->query($sql);
        }
    }

    public function setAccountAS(&$bean, $event, $arguments)
    {
        global $sugar_config;
        if ($sugar_config['lionixcrm']['business_type']=='b2c') {
            $newAccount = BeanFactory::newBean('Accounts');
            if (!empty($bean->account_id)) {
                $query = "
                    SELECT a.id
                    FROM accounts_contacts acco
                    LEFT JOIN accounts a ON acco.account_id = a.id
                    LEFT JOIN accounts_cstm ac ON a.id = ac.id_c
                    WHERE acco.deleted = 0
                    AND a.deleted = 0
                    AND ac.tipocedula_c = 'NACIONAL'
                    AND contact_id = '{$bean->id}'
                    ORDER BY acco.date_modified desc
                    LIMIT 1
                ";
                $nacional_account_id = $bean->db->getOne($query);
                $newAccount->retrieve($nacional_account_id);
            }
            $newAccount->name = trim("{$bean->first_name} {$bean->last_name} {$bean->lastname2_c}");
            $newAccount->tipocedula_c = 'NACIONAL';
            $newAccount->cedula_c = $bean->cedula_c;
            $newAccount->contact_id = $bean->id;
            $newAccount->save();
        }
    }
}
