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
    // campaigns
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
    // prospect_lists
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
    // contacts
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
    // schedulers
    installLog('LionixCRM starting to add custom schedulers...');
    $query = "INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up)
    VALUES ('infoticos', '0', utc_timestamp(), utc_timestamp(), '1', '1', '99- LionixCRM - INFOTICOS - Check against TSE CR', 'function::infoticos', '2005-01-01 07:00:00', '*/2::*::*::*::*', utc_timestamp(), 'Active', '0')";
    $db->query($query);
    $query = "INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up) VALUES ('updateprospectlistprospects', '0', utc_timestamp(), utc_timestamp(), '1', '1', '01- LionixCRM - Prospect List Prospects Update 2:00am', 'function::updateProspectListProspects', '2005-01-01 07:00:00', '00::02::*::*::*', utc_timestamp(), 'Active', '0')";
    $db->query($query);
    $query = "INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up) VALUES ('campaignlogdeleter', '0', utc_timestamp(), utc_timestamp(), '1', '1', '02- LionixCRM - CampaignLogDeletEr - 8:00am', 'function::campaignLogDeletEr', '2005-01-01 07:00:00', '00::08::*::*::*', utc_timestamp(), 'Active', '0')";
    $db->query($query);
    $query = "INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up) VALUES ('emailmaner', '0', utc_timestamp(), utc_timestamp(), '1', '1', '03- LionixCRM - EmailManEr - 9:00am', 'function::emailManEr', '2005-01-01 07:00:00', '00::09::*::*::*', utc_timestamp(), 'Active', '0')";
    installLog('...LionixCRM added custom schedulers successfully.');
    $db->query($query);
    // views and store procedures
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
    // birthday emails
    installLog('LionixCRM starting to add birthday email campaign...');
    $query = "INSERT INTO campaigns (id, name, date_entered, date_modified, modified_user_id, created_by, deleted, assigned_user_id, tracker_count, refer_url, start_date, end_date, status, impressions, currency_id, campaign_type, content) VALUES ('daily-email-bday-congrats-campaign', 'daily_email_birthday_congratulations_campaign', utc_timestamp(), utc_timestamp(), '1', '1', '0', '1', '0', 'http://', '1980-02-01', '1980-02-01', 'Active', '0', '-99', 'Email', 'Listas de Público Objetivo = daily_email_birthday_congratulations_contacts\r\nPlantillas de Email = z_daily_email_birthday_congratulations_template\r\n\r\nEsta campaña se usa de template para alimentar la tabla emailman mediante varios scheduler.\r\n\r\nSon 3: \r\n01- LionixCRM - Prospect List Prospects Update 2:00am\r\n02- LionixCRM - CampaignLogDeletEr - 8:00am\r\n03- LionixCRM - EmailManEr - 9:00am\r\n\r\n-- última línea')";
    $db->query($query);
    $query = "INSERT INTO campaigns_cstm (id_c, emailmaner_c, clearcamplogdaily_c) VALUES ('daily-email-bday-congrats-campaign', '1', '1')";
    $db->query($query);
    $query = "INSERT INTO email_marketing (id, deleted, date_entered, date_modified, modified_user_id, created_by, name, from_name, inbound_email_id, date_start, template_id, status, campaign_id, outbound_email_id, all_prospect_lists)
    VALUES ('daily-email-bday-congrats-campaign', '0', utc_timestamp(), utc_timestamp(), '1', '1', 'daily_email_bday_congrats', 'Fanatics Club', null, utc_timestamp(), 'z_daily_email_bday_congrats_template', 'active', 'daily-email-bday-congrats-campaign', '0', '0')";
    $db->query($query);
    $query = "INSERT INTO prospect_list_campaigns (id, prospect_list_id, campaign_id, date_modified, deleted) VALUES ('daily-email-bday-congrats-campaign', 'daily-email-bday-congrats-contacts', 'daily-email-bday-congrats-campaign', utc_timestamp(), '0')";
    $db->query($query);
    $query = "INSERT INTO email_templates (id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, type) VALUES ('z_daily_email_bday_congrats_template', utc_timestamp(), utc_timestamp(), '1', '1', 'off', 'z_daily_email_birthday_congratulations_template', 'z_daily_email_birthday_congratulations_template', 'Te deseamos un ¡Feliz cumpleaños!', '¡Muy feliz cumpleaños $contact_first_name!', '<div class=\"mozaik-inner\" style=\"font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px 30px;margin:0px;\"><p style=\"text-align:left;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;\"><span style=\"font-family:tahoma, arial, helvetica, sans-serif;font-size:24px;line-height:38.4px;color:#444444;padding:0px;margin:0px;\">¡Muy feliz cumpleaños $contact_first_name!</span></p><p style=\"font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;\"><img style=\"margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;\" src=\"custom/lionix/img/happy-birthday-to-you.jpg\" width=\"842\" height=\"500\" alt=\"happy-birthday-to-you.jpg\" /></p><p style=\"font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;\"><br style=\"font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px;\" /></p><div class=\"mozaik-clear\" style=\"font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;height:0px;\"><br style=\"font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;\" /></div></div>', '0', '1', '0', 'campaign')
    ";
    $db->query($query);
    installLog('...LionixCRM added birthday email campaign successfully.');
    // el fin
    $finmsg = 'LionixCRM install finished';
    installLog($finmsg);

    return $finmsg;
}
