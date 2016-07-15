<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// This runs after all modules tables are created via installerHook called on performSetup.php
// function post_createAllModuleTables(){
// return 'LionixCRM modules tables finished';
// }

// This runs after installation is completed via installerHook called on performSetup.php
function post_installModules()
{
    global $sugar_config;
    $db = DBManagerFactory::getInstance();
    $database = $sugar_config['dbconfig']['db_name'];
    // lxcode_c
    installLog('LionixCRM starting to add lxcode_c to all custom and audit tables...');
    $query = "
        SELECT TABLE_NAME
        FROM information_schema.tables
        WHERE table_schema = '{$database}'
            AND (TABLE_NAME LIKE '%cstm'
                 OR TABLE_NAME LIKE '%audit')
    ";
    $result = $db->query($query);
    while (($row = $db->fetchByAssoc($result)) != null) {
        $query = "ALTER TABLE {$row['TABLE_NAME']} ADD lxcode_c int AUTO_INCREMENT NOT NULL UNIQUE";
        $db->query($query);
    }
    installLog('...LionixCRM added lxcode_c to all custom and audit tables successfully.');
    // acl_roles
    installLog('LionixCRM starting to add roles...');
    $roles = array(
                array('id' => 'audit-role', 'name' => 'Audit', 'description' => 'Read only access to all data.'),
                array('id' => 'manager-role', 'name' => 'Manager', 'description' => 'Full access to all data.'),
                array('id' => 'sales-role', 'name' => 'Sales', 'description' => 'Partial access to some data.'),
            );
    foreach ($roles as $role) {
        $query = "
            INSERT INTO acl_roles (`id`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `name`, `description`, `deleted`)
            VALUES ('{$role['id']}', utc_timestamp(), utc_timestamp(), '1', '1', '{$role['name']}', '{$role['description']}', '0')
        ";
        $db->query($query);
        $query = "
            INSERT INTO acl_roles_actions (id, role_id, action_id, access_override, date_modified, deleted)
            SELECT uuid(),
                   '{$role['id']}',
                   id,
                   aclaccess,
                   utc_timestamp(),
                   0
            FROM acl_actions
            WHERE id NOT IN
                    (SELECT action_id
                     FROM acl_roles_actions
                     WHERE role_id = '{$role['id']}')
        ";
        $db->query($query);
        switch ($role['id']) {
            case 'audit-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '-99'
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('delete',
                                            'edit',
                                            'export',
                                            'import',
                                            'massupdate'))
                ";
                $db->query($query);
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '89'
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('access'))
                ";
                $db->query($query);
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '90'
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('list',
                                            'view' ))
                ";
                $db->query($query);
                break;
                case 'sales-role':
                    $query = "
                        UPDATE acl_roles_actions
                        SET access_override = '-99'
                        WHERE role_id = '{$role['id']}'
                            AND action_id IN
                                (SELECT id
                                 FROM acl_actions
                                 WHERE name IN ('export',
                                                'massupdate'))
                    ";
                    $db->query($query);
                    $query = "
                        UPDATE acl_roles_actions
                        SET access_override = '75'
                        WHERE role_id = '{$role['id']}'
                            AND action_id IN
                                (SELECT id
                                 FROM acl_actions
                                 WHERE name IN ('delete',
                                                'edit'))
                    ";
                    $db->query($query);
                    $query = "
                        UPDATE acl_roles_actions
                        SET access_override = '89'
                        WHERE role_id = '{$role['id']}'
                            AND action_id IN
                                (SELECT id
                                 FROM acl_actions
                                 WHERE name IN ('access'))
                    ";
                    $db->query($query);
                    $query = "
                        UPDATE acl_roles_actions
                        SET access_override = '90'
                        WHERE role_id = '{$role['id']}'
                            AND action_id IN
                                (SELECT id
                                 FROM acl_actions
                                 WHERE name IN ('import',
                                                'list',
                                                'view'))
                    ";
                    $db->query($query);
                    break;
        }
    }
    installLog('...LionixCRM added roles successfully.');

    installLog('LionixCRM starting to add custom fields on campaigns module...');
    $query = 'CREATE TABLE campaigns_cstm (id_c char(36) NOT NULL, PRIMARY KEY (id_c)) CHARACTER SET utf8 COLLATE utf8_general_ci';
    $db->query($query);
    $query = "ALTER TABLE campaigns_cstm add COLUMN emailmaner_c bool DEFAULT '0' NULL";
    $db->query($query);
    $query = "ALTER TABLE campaigns_cstm add COLUMN clearcamplogdaily_c bool DEFAULT '0' NULL";
    $db->query($query);
    $query = "INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
    VALUES ('Campaignsemailmaner_c','emailmaner_c','LBL_EMAILMANER','','','Campaigns','bool',255,0,'0',utc_timestamp(),0,0,0,0,1,'false','','','','')";
    $db->query($query);
    $query = "INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
    VALUES ('Campaignsclearcamplogdaily_c','clearcamplogdaily_c','LBL_CLEARCAMPLOGDAILY','','','Campaigns','bool',255,0,'0',utc_timestamp(),0,0,0,0,1,'false','','','','')";
    $db->query($query);
    installLog('...LionixCRM added custom fields on campaigns module successfully.');

    installLog('LionixCRM starting to add custom fields and records on prospect_lists module...');
    $query = 'CREATE TABLE prospect_lists_cstm (id_c char(36) NOT NULL, PRIMARY KEY (id_c)) CHARACTER SET utf8 COLLATE utf8_general_ci';
    $db->query($query);
    $query = "ALTER TABLE prospect_lists_cstm add COLUMN autofill_c bool DEFAULT '0' NULL";
    $db->query($query);
    $query = "ALTER TABLE prospect_lists_cstm add COLUMN autoclean_c bool DEFAULT '0' NULL";
    $db->query($query);
    $query = "INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
    VALUES ('ProspectListsautofill_c','autofill_c','LBL_AUTOFILL','autofill must be false until there\'s certainty that would be use','','ProspectLists','bool',255,0,'0',utc_timestamp(),0,0,0,0,1,'true','','','','')";
    $db->query($query);
    $query = "INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
    VALUES ('ProspectListsautoclean_c','autoclean_c','LBL_AUTOCLEAN','autoclean must be false until there\'s certainty that would be use','','ProspectLists','bool',255,0,'0',utc_timestamp(),0,0,0,0,1,'true','','','','')";
    $db->query($query);
    $query = "INSERT INTO prospect_lists_cstm (id_c ,autoclean_c ,autofill_c ) VALUES ('daily-email-bday-congrats-contacts' ,'1' ,'1' )";
    $db->query($query);
    $query = "INSERT INTO prospect_lists (assigned_user_id,id,name,list_type,date_entered,date_modified,modified_user_id,created_by,deleted,description,domain_name)
    VALUES ('1','daily-email-bday-congrats-contacts','daily_email_birthday_congratulations_contacts','default',utc_timestamp(),utc_timestamp(),'1','1',0,'Autoclean and autofill must be set to true always, this list is used by emailManEr function on schedulers.','')";
    $db->query($query);
    installLog('...LionixCRM added custom fields and records on prospect_lists module successfully.');

    installLog('LionixCRM starting to add custom fields on contacts module...');
    $query = 'ALTER TABLE contacts_cstm add COLUMN soundex_c varchar(3) NULL';
    $db->query($query);
    $query = 'ALTER TABLE contacts_cstm add COLUMN cedula_c varchar(255) NULL';
    $db->query($query);
    $query = "INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
    VALUES ('Contactssoundex_c','soundex_c','LBL_SOUNDEX','Allowed values are AAA,AA,A,B,C,D,NER,MAL,SIN','','Contacts','varchar',3,0,'',utc_timestamp(),0,0,0,0,1,'true','','','','')";
    $db->query($query);
    $query = "INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
    VALUES ('Contactscedula_c','cedula_c','LBL_CEDULA','','','Contacts','varchar',255,0,'','2016-07-14 20:08:17',0,0,0,0,1,'true','','','','')";
    $db->query($query);
    installLog('...LionixCRM added custom fields on contacts module successfully.');

    installLog('LionixCRM starting to add custom store procedures and views into database...');
    $queries_array = array(
        'custom/lionix/query/prospect_list/vista_cron_pl_daily_email_birthday_congratulations_contacts.sql',
        'custom/lionix/query/store_procedures/sp_infoticos.sql',
    );
    foreach ($queries_array as $current_file) {
        if (file_exists($current_file)) {
            $query = file_get_contents($current_file);
            $db->query($query);
            installLog("...{$current_file} executed");
        }
    }
    installLog('...LionixCRM added custom store procedures and views into database successfully.');

    // el fin
    $finmsg = 'LionixCRM install finished';
    installLog($finmsg);

    return $finmsg;
}
