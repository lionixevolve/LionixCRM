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

    public function setStatusCBS(&$bean, $event, $arguments)
    {
        if (empty($bean->status_c)) {
            $bean->status_c = $bean->sales_stage.'_new';
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

    public function setPreviousSalesStageCAS(&$bean, $event, $arguments)
    {
        // call on changed records only
        if (isset(self::$fetchedRow[$bean->id])) {
            // execute changed record business process
            if ($bean->sales_stage != self::$fetchedRow[$bean->id]['sales_stage']) {
                $previoussalesstage = self::$fetchedRow[$bean->id]['sales_stage'];
                $query = "
                    update opportunities o
                    left join opportunities_cstm oc on o.id = oc.id_c
                    set
                    oc.previoussalesstage_c = '{$previoussalesstage}',
                    oc.previousstatus_c = oc.status_c,
                    oc.status_c = '{$bean->sales_stage}_new'
                    where o.deleted = 0
                    and o.id = '{$bean->id}'
                ";
                $bean->db->query($query);
            }
        }
    }

    public function setMainContactCAS(&$bean, $event, $arguments)
    {
        global $sugar_config;
        $bean->custom_fields->retrieve();

        if ($bean->maincontact_c=='new') {
            if (($sugar_config['lionixcrm']['business_type']=='b2c')) {
                $newAccount = BeanFactory::newBean('Accounts');
                $newAccount->name = "{$bean->maincontactfirstname_c} {$bean->maincontactlastname_c} {$bean->maincontactlastname2_c}";
                $newAccount->tipocedula_c = 'NACIONAL';
                $newAccount->cedula_c = $bean->maincontactcedula_c;
                $newAccount->save();
                $bean->account_id = $newAccount->id;
            }

            $newContact = BeanFactory::newBean('Contacts');
            $newContact->account_id = $bean->account_id;
            $newContact->first_name = $bean->maincontactfirstname_c;
            $newContact->last_name = $bean->maincontactlastname_c;
            $newContact->lastname2_c = $bean->maincontactlastname2_c;
            $newContact->phone_work = $bean->maincontactphonework_c;
            $newContact->email1 = $bean->maincontactemailaddress_c;
            $newContact->title = $bean->maincontacttitle_c;
            $newContact->cedula_c = $bean->maincontactcedula_c;
            $newContact->assigned_user_id = $bean->created_by;
            $newContact->save();
            $bean->maincontact_c = $newContact->id;
            $query = "
                update opportunities_cstm
                set maincontact_c = '{$newContact->id}',
                maincontactfirstname_c = null,
                maincontactlastname_c = null,
                maincontactlastname2_c = null,
                maincontactphonework_c = null,
                maincontactemailaddress_c = null,
                maincontacttitle_c = null,
                maincontactcedula_c = null
                where id_c = '{$bean->id}'
            ";
            $bean->db->query($query);
        }

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
                insert into opportunities_contacts (id,contact_id,opportunity_id,contact_role,date_modified,deleted)
                values (uuid(),'{$bean->maincontact_c}','{$bean->id}','Primary Decision Maker',utc_timestamp(),0)
            ";
            $bean->db->query($query);
        } else {
            $query = "
                update opportunities_contacts
                set contact_role = ''
                where deleted = 0
                and opportunity_id = '{$bean->id}'
            ";
            $bean->db->query($query);
            $query = "
                update opportunities_contacts
                set contact_role = 'Primary Decision Maker'
                where deleted = 0
                and opportunity_id = '{$bean->id}'
                and contact_id = '{$bean->maincontact_c}'
            ";
            $bean->db->query($query);
        }
    }

    public function setMissingNotesLinksAS(&$bean, $event, $arguments)
    {
        global $sugar_config;
        $files_fields = $sugar_config['lionixcrm']['modules']['opportunities']['upload_files_fields'];
        foreach ($files_fields as $ff) {
            $sql = "
                UPDATE opportunities o
                LEFT JOIN opportunities_cstm oc ON o.id = oc.id_c
                LEFT JOIN notes n ON oc.{$ff['field_name']} LIKE CONCAT('%',n.id,'%')
                SET n.parent_id = o.id
                WHERE o.deleted = 0
                AND n.deleted = 0
                AND n.parent_type = 'opportunities'
                AND n.parent_id = ''
                AND o.id = '{$bean->id}'
        ";
            $bean->db->query($sql);
        }
    }
}
