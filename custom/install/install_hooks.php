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
    // cases
    installLog('LionixCRM starting to add custom fields on cases module...');
    $query = "ALTER TABLE cases_cstm add COLUMN elapsedtimeinmins_c int(255) DEFAULT '0' NULL";
    $db->query($query);
    $query = "INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4) VALUES ('Caseselapsedtimeinmins_c','elapsedtimeinmins_c','LBL_ELAPSEDTIMEINMINS','This time is calculated on schedulers with function WORKDAY_TIME_DIFF_HOLIDAY_TABLE','This time is calculated on schedulers with function WORKDAY_TIME_DIFF_HOLIDAY_TABLE','Cases','int','255','0','0',utc_timestamp(),'0','0','0','0','1','false','','','1','')";
    $db->query($query);
    installLog('...LionixCRM added custom fields on cases module successfully.');
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
    VALUES ('infoticos', '0', utc_timestamp(), utc_timestamp(), '1', '1', '99- LionixCRM - INFOTICOS - Check against TSE CR', 'function::infoticos', '1980-02-01 06:00:00', '*/2::*::*::*::*', utc_timestamp(), 'Active', '0')";
    $db->query($query);
    $query = "INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up)
    VALUES ('updateelapsedtimeinmins', '0', utc_timestamp(), utc_timestamp(), '1', '1', '99- LionixCRM - updateElapsedTimeInMins', 'function::updateElapsedTimeInMins', '1980-02-01 06:00:00', '*::*::*::*::*', utc_timestamp(), 'Active', '0')";
    $db->query($query);
    $query = "INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up) VALUES ('updateprospectlistprospects', '0', utc_timestamp(), utc_timestamp(), '1', '1', '01- LionixCRM - Prospect List Prospects Update 2:00am', 'function::updateProspectListProspects', '1980-02-01 06:00:00', '00::02::*::*::*', utc_timestamp(), 'Active', '0')";
    $db->query($query);
    $query = "INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up) VALUES ('campaignlogdeleter', '0', utc_timestamp(), utc_timestamp(), '1', '1', '02- LionixCRM - CampaignLogDeletEr - 8:00am', 'function::campaignLogDeletEr', '1980-02-01 06:00:00', '00::08::*::*::*', utc_timestamp(), 'Active', '0')";
    $db->query($query);
    $query = "INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up) VALUES ('emailmaner', '0', utc_timestamp(), utc_timestamp(), '1', '1', '03- LionixCRM - EmailManEr - 9:00am', 'function::emailManEr', '1980-02-01 06:00:00', '00::09::*::*::*', utc_timestamp(), 'Active', '0')";
    installLog('...LionixCRM added custom schedulers successfully.');
    $db->query($query);
    // views and store procedures
    installLog('LionixCRM starting to add custom store procedures, functions and views into database...');
    $queries_array = array(
        'custom/lionix/query/prospect_list/vista_cron_pl_daily_email_birthday_congratulations_contacts.sql',
        'custom/lionix/query/store_procedures/sp_infoticos.sql',
        'custom/lionix/query/store_functions/fn_create_holiday_table.sql',
        'custom/lionix/query/store_functions/fn_workday_time_diff_holiday_table.sql',
    );
    //TODO: call terminal with mysql database < file.sql
    foreach ($queries_array as $current_file) {
        if (file_exists($current_file)) {
            $query = file_get_contents($current_file);
            $db->query($query);
            installLog("...{$current_file} executed");
        }
    }
    installLog('...LionixCRM added custom store procedures and views into database successfully.');
    // holiday_table records
    installLog('LionixCRM starting to add holiday records into database...');

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2016-01-01', '2016-01-01', DATE_FORMAT('2016-01-01','%W'), 'Año Nuevo', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2016-04-11', '2016-04-11', DATE_FORMAT('2016-04-11','%W'), 'Día de Juan Santamaría', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2016-05-01', '2016-05-01', DATE_FORMAT('2016-05-01','%W'), 'Día Internacional del Trabajo', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2016-07-25', '2016-07-25', DATE_FORMAT('2016-07-25','%W'), 'Anexión del Partido de Nicoya a Costa Rica', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2016-08-02', '2016-08-02', DATE_FORMAT('2016-08-02','%W'), 'Día de la Virgen de los Ángeles', 'CR') -- Pago no obligatorio";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2016-08-15', '2016-08-15', DATE_FORMAT('2016-08-15','%W'), 'Día de la Madre', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2016-09-15', '2016-09-15', DATE_FORMAT('2016-09-15','%W'), 'Independencia de Costa Rica', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2016-10-12', '2016-10-12', DATE_FORMAT('2016-10-12','%W'), 'Día de las Culturas', 'CR') -- Pago no obligatorio";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2016-12-25', '2016-12-25', DATE_FORMAT('2016-12-25','%W'), 'Navidad', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2017-01-01', '2017-01-01', DATE_FORMAT('2017-01-01','%W'), 'Año Nuevo', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2017-04-11', '2017-04-11', DATE_FORMAT('2017-04-11','%W'), 'Día de Juan Santamaría', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2017-05-01', '2017-05-01', DATE_FORMAT('2017-05-01','%W'), 'Día Internacional del Trabajo', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2017-07-25', '2017-07-25', DATE_FORMAT('2017-07-25','%W'), 'Anexión del Partido de Nicoya a Costa Rica', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2017-08-02', '2017-08-02', DATE_FORMAT('2017-08-02','%W'), 'Día de la Virgen de los Ángeles', 'CR') -- Pago no obligatorio";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2017-08-15', '2017-08-15', DATE_FORMAT('2017-08-15','%W'), 'Día de la Madre', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2017-09-15', '2017-09-15', DATE_FORMAT('2017-09-15','%W'), 'Independencia de Costa Rica', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2017-10-12', '2017-10-12', DATE_FORMAT('2017-10-12','%W'), 'Día de las Culturas', 'CR') -- Pago no obligatorio";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2017-12-25', '2017-12-25', DATE_FORMAT('2017-12-25','%W'), 'Navidad', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2018-01-01', '2018-01-01', DATE_FORMAT('2018-01-01','%W'), 'Año Nuevo', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2018-04-11', '2018-04-11', DATE_FORMAT('2018-04-11','%W'), 'Día de Juan Santamaría', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2018-05-01', '2018-05-01', DATE_FORMAT('2018-05-01','%W'), 'Día Internacional del Trabajo', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2018-07-25', '2018-07-25', DATE_FORMAT('2018-07-25','%W'), 'Anexión del Partido de Nicoya a Costa Rica', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2018-08-02', '2018-08-02', DATE_FORMAT('2018-08-02','%W'), 'Día de la Virgen de los Ángeles', 'CR') -- Pago no obligatorio";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2018-08-15', '2018-08-15', DATE_FORMAT('2018-08-15','%W'), 'Día de la Madre', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2018-09-15', '2018-09-15', DATE_FORMAT('2018-09-15','%W'), 'Independencia de Costa Rica', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2018-10-12', '2018-10-12', DATE_FORMAT('2018-10-12','%W'), 'Día de las Culturas', 'CR') -- Pago no obligatorio";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2018-12-25', '2018-12-25', DATE_FORMAT('2018-12-25','%W'), 'Navidad', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2019-01-01', '2019-01-01', DATE_FORMAT('2019-01-01','%W'), 'Año Nuevo', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2019-04-11', '2019-04-11', DATE_FORMAT('2019-04-11','%W'), 'Día de Juan Santamaría', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2019-05-01', '2019-05-01', DATE_FORMAT('2019-05-01','%W'), 'Día Internacional del Trabajo', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2019-07-25', '2019-07-25', DATE_FORMAT('2019-07-25','%W'), 'Anexión del Partido de Nicoya a Costa Rica', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2019-08-02', '2019-08-02', DATE_FORMAT('2019-08-02','%W'), 'Día de la Virgen de los Ángeles', 'CR') -- Pago no obligatorio";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2019-08-15', '2019-08-15', DATE_FORMAT('2019-08-15','%W'), 'Día de la Madre', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2019-09-15', '2019-09-15', DATE_FORMAT('2019-09-15','%W'), 'Independencia de Costa Rica', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2019-10-12', '2019-10-12', DATE_FORMAT('2019-10-12','%W'), 'Día de las Culturas', 'CR') -- Pago no obligatorio";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2019-12-25', '2019-12-25', DATE_FORMAT('2019-12-25','%W'), 'Navidad', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2020-01-01', '2020-01-01', DATE_FORMAT('2020-01-01','%W'), 'Año Nuevo', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2020-04-11', '2020-04-11', DATE_FORMAT('2020-04-11','%W'), 'Día de Juan Santamaría', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2020-05-01', '2020-05-01', DATE_FORMAT('2020-05-01','%W'), 'Día Internacional del Trabajo', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2020-07-25', '2020-07-25', DATE_FORMAT('2020-07-25','%W'), 'Anexión del Partido de Nicoya a Costa Rica', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2020-08-02', '2020-08-02', DATE_FORMAT('2020-08-02','%W'), 'Día de la Virgen de los Ángeles', 'CR') -- Pago no obligatorio";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2020-08-15', '2020-08-15', DATE_FORMAT('2020-08-15','%W'), 'Día de la Madre', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2020-09-15', '2020-09-15', DATE_FORMAT('2020-09-15','%W'), 'Independencia de Costa Rica', 'CR')";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2020-10-12', '2020-10-12', DATE_FORMAT('2020-10-12','%W'), 'Día de las Culturas', 'CR') -- Pago no obligatorio";
    $db->query($query);

    $query = "INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
    VALUES ('2020-12-25', '2020-12-25', DATE_FORMAT('2020-12-25','%W'), 'Navidad', 'CR')";
    $db->query($query);
    installLog('...LionixCRM added holiday records into database successfully.');

    // birthday emails
    installLog('LionixCRM starting to add birthday email campaign (status=inactive)...');
    $query = "INSERT INTO campaigns (id, name, date_entered, date_modified, modified_user_id, created_by, deleted, assigned_user_id, tracker_count, refer_url, start_date, end_date, status, impressions, currency_id, campaign_type, content) VALUES ('daily-email-bday-congrats-campaign', 'daily_email_birthday_congratulations_campaign', utc_timestamp(), utc_timestamp(), '1', '1', '0', '1', '0', 'http://', '1980-02-01', '1980-02-01', 'Inactive', '0', '-99', 'Email', 'Listas de Público Objetivo = daily_email_birthday_congratulations_contacts\r\nPlantillas de Email = z_daily_email_birthday_congratulations_template\r\n\r\nEsta campaña se usa de template para alimentar la tabla emailman mediante varios scheduler.\r\n\r\nSon 3: \r\n01- LionixCRM - Prospect List Prospects Update 2:00am\r\n02- LionixCRM - CampaignLogDeletEr - 8:00am\r\n03- LionixCRM - EmailManEr - 9:00am\r\n\r\n-- última línea')";
    $db->query($query);
    $query = "INSERT INTO campaigns_cstm (id_c, emailmaner_c, clearcamplogdaily_c) VALUES ('daily-email-bday-congrats-campaign', '1', '1')";
    $db->query($query);
    /*z_daily_email_bday_congrats_template*/
    $query = "INSERT INTO email_marketing (id, deleted, date_entered, date_modified, modified_user_id, created_by, name, from_name, inbound_email_id, date_start, template_id, status, campaign_id, outbound_email_id, all_prospect_lists)
    VALUES ('daily-email-bday-congrats-campaign', '0', utc_timestamp(), utc_timestamp(), '1', '1', 'daily_email_bday_congrats', 'Fanatics Club', null, utc_timestamp(), 'bda8bda8-bda8-bda8-bda8-bda8bda8bda8', 'active', 'daily-email-bday-congrats-campaign', '0', '0')";
    $db->query($query);
    $query = "INSERT INTO prospect_list_campaigns (id, prospect_list_id, campaign_id, date_modified, deleted) VALUES ('daily-email-bday-congrats-campaign', 'daily-email-bday-congrats-contacts', 'daily-email-bday-congrats-campaign', utc_timestamp(), '0')";
    $db->query($query);
    /*z_daily_email_bday_congrats_template*/
    $query = "INSERT INTO email_templates (id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, type) VALUES ('bda8bda8-bda8-bda8-bda8-bda8bda8bda8', utc_timestamp(), utc_timestamp(), '1', '1', 'off', 'z_daily_email_birthday_congratulations_template', 'This template is used to send automatically email congratulations to contacts on daily_email_birthday_congratulations_contacts target list.', 'Te deseamos un ¡Feliz cumpleaños!', '¡Muy feliz cumpleaños \$contact_first_name!', '<div class=\"mozaik-inner\" style=\"font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px 30px;margin:0px;\"><p style=\"text-align:left;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;\"><span style=\"font-family:tahoma, arial, helvetica, sans-serif;font-size:24px;line-height:38.4px;color:#444444;padding:0px;margin:0px;\">¡Muy feliz cumpleaños \$contact_first_name!</span></p><p style=\"font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;\"><img style=\"margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;\" src=\"custom/lionix/img/happy-birthday-to-you.jpg\" alt=\"happy-birthday-to-you.jpg\" width=\"842\" height=\"500\" /></p><p style=\"font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;\"></p><div class=\"mozaik-clear\" style=\"font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;height:0px;\"></div></div>', '0', '1', '0', 'campaign')
    ";
    $db->query($query);
    $query = "INSERT INTO email_templates
(id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, `type`)
VALUES('01abcdef-efef-efef-efef-efefefefefef', '2015-12-10 23:53:21.000', '2016-11-26 02:12:38.000', '1', '1', 'off', 'z_NOT_OP_sales_stage_change', 'LionixCRM - Notificación de Oportunidad', 'LionixCRM - Notificación de Oportunidad', 'LionixCRM - Notificación Oportunidad

Atención! ->  $opportunity_name se encuentra en la etapa $opportunity_sales_stage

¡LionixCRM te desea una exitosa semana!', '<div class="mozaik-inner" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px 30px;margin:0px;"><table style="width:100%;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:3px 3px 3px 0px;margin:0px;" border="0" cellspacing="0" cellpadding="0" bgcolor="#3986B3" class="mce-item-table"><tbody style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;"><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;" align="center"><table style="width:700px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:3px 3px 3px 0px;margin:0px;" border="0" cellspacing="0" cellpadding="0" align="center" class="mce-item-table"><tbody style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;"><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;" align="left"><table style="width:700px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:3px 3px 3px 0px;margin:0px;" border="0" cellspacing="0" cellpadding="0" class="mce-item-table"><tbody style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;height:29px;"><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;height:29px;"></td></tr><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;height:75px;"><td style="padding:3px 3px 3px 0px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;margin:0px;height:75px;" align="left" bgcolor="#ffffff"><table style="width:700px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:3px 3px 3px 0px;margin:0px;" border="0" cellspacing="0" cellpadding="0" align="center" class="mce-item-table"><tbody style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;"><td style="padding:3px 3px 3px 0px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;margin:0px;" valign="top" width="190"><a style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;" href="http://mrc.crm.cr"><img style="border:0px;margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;" title="lionix_logo.png" src="http://mrc.crm.cr/custom/themes/default/images/company_logo.png" alt="company_logo.png" width="200" height="77" border="0" /></a></td><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;"><table style="width:100%;height:46px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:3px 3px 3px 0px;margin:0px;" border="0" cellspacing="0" cellpadding="0" class="mce-item-table"><tbody style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;"><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(83,77,100);padding:3px 3px 3px 0px;margin:0px;text-align:left;"><span style="padding:0px 0px 21px;color:#666666;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;margin:0px;"><span style="font-size:20px;font-family:Verdana, Geneva, sans-serif;line-height:32px;color:#444444;padding:0px;margin:0px;">  LionixCRM - Notificación<br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;" /></span></span></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;height:285px;"><td style="padding:3px 3px 3px 0px;color:#666666;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;margin:0px;height:285px;" align="left" bgcolor="#ffffff"><table style="width:100%;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:3px 3px 3px 0px;margin:0px;" border="0" summary="1" cellspacing="0" cellpadding="2" class="mce-item-table"><tbody style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;"><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;" valign="top"><table style="width:100%;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:3px 3px 3px 0px;margin:0px;" border="0" class="mce-item-table"><tbody style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;"><td style="font-family:Verdana, Geneva, sans-serif;font-size:20px;color:#000066;line-height:32px;padding:3px 3px 3px 0px;margin:0px;" align="center">Atención! -> <a style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;" href="http://netcom.crm.cr/index.php?module=Opportunities&action=DetailView&record=$opportunity_id">$opportunity_name</a> se encuentra en la etapa <a style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;" href="http://netcom.crm.cr/index.php?module=Opportunities&action=DetailView&record=$opportunity_id">$opportunity_sales_stage</a></td></tr><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;"><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;"></td></tr></tbody></table></td></tr></tbody></table><table style="width:100%;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:3px 3px 3px 0px;margin:0px;" border="0" cellspacing="0" cellpadding="0" class="mce-item-table"><tbody style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;"><td style="padding:3px 3px 3px 0px;border-right:1px solid #aaaaaa;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;margin:0px;" valign="top" width="166" height="170"><table style="width:145px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:3px 3px 3px 0px;margin:0px;" border="0" cellspacing="0" cellpadding="0" class="mce-item-table"><tbody style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;"><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;"></td><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;"></td><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;"></td><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;"></td><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;"></td><td style="padding:3px 3px 3px 0px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;margin:0px;" width="21"><a style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;" href="http://www.facebook.com/lionix"><img style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;" title="logo_face_small.gif" src="http://www.lionix.com/images/logo_face_small.gif" alt="" width="15" height="14" border="0" /><br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;" /></a></td><td style="padding:3px 3px 3px 0px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;margin:0px;" width="124"><span style="font-size:10px;line-height:13px;font-family:Arial, Helvetica, sans-serif;color:#444444;padding:0px;margin:0px;"><a style="color:#aaaaaa;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;padding:0px;margin:0px;" href="http://www.lionix.com">LIONIX</a></span></td></tr></tbody></table></td><td style="padding:3px 3px 3px 0px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;margin:0px;" valign="top"><table style="width:100%;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:3px 3px 3px 0px;margin:0px;" border="0" cellspacing="0" cellpadding="0" class="mce-item-table"><tbody style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;"><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;"><p style="padding:0px 0px 10px;color:#666666;font-size:18px;font-family:Arial, Helvetica, sans-serif;line-height:28.8px;margin:0px;text-align:center;">¡LionixCRM te desea una exitosa semana!</p></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><div class="mozaik-clear" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px;height:0px;"><br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px;" /></div><div id="sugar_text_mceResizeHandlenw" class="mce-resizehandle" style="margin:0px;padding:0px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);"></div><div id="sugar_text_mceResizeHandlene" class="mce-resizehandle" style="margin:0px;padding:0px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);"></div><div id="sugar_text_mceResizeHandlese" class="mce-resizehandle" style="margin:0px;padding:0px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);"></div><div id="sugar_text_mceResizeHandlesw" class="mce-resizehandle" style="margin:0px;padding:0px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);"></div></div>', 0, '1', 0, NULL);
";
$db->query($query);
    installLog('...LionixCRM added birthday email campaign (status=inactive) successfully.');
    // lxcode_c
    installLog('LionixCRM starting to add lxcode_c to all custom and audit tables...');
    $query = "
        SELECT TABLE_NAME,
               IF(TABLE_NAME LIKE '%_cstm',REPLACE(CONCAT(UPPER(MID(TABLE_NAME, 1, 1)),MID(REPLACE(TABLE_NAME, '_cstm', ''), 2)),'_lists','Lists'),'') AS 'MODULE_NAME'
        FROM information_schema.tables
        WHERE table_schema = '{$database}'
            AND (TABLE_NAME LIKE '%cstm'
                 OR TABLE_NAME LIKE '%audit')
    ";
    $result = $db->query($query);
    while (($row = $db->fetchByAssoc($result)) != null) {
        $query = "ALTER TABLE {$row['TABLE_NAME']} ADD lxcode_c int AUTO_INCREMENT NOT NULL UNIQUE";
        $db->query($query);
        if(!empty($row['MODULE_NAME'])){
            $query = "INSERT INTO `fields_meta_data` (`id`, `name`, `vname`, `custom_module`, `type`, `len`, `required`, `date_modified`, `deleted`, `audited`, `massupdate`, `duplicate_merge`, `reportable`, `importable`, `ext3`) VALUES ('{$row['MODULE_NAME']}lxcode_c', 'lxcode_c', 'LBL_LXCODE', '{$row['MODULE_NAME']}', 'int', '255', '0', utc_timestamp(), '0', '0', '0', '0', '1', 'false', '1')";
            $db->query($query);
        }
    }
    installLog('...LionixCRM added lxcode_c to all custom and audit tables successfully.');
    // LionixCRM configuration values
    // TODO: Ask values on installation
    installLog('LionixCRM starting to add sugar_config values...');
    global $sugar_config;
    $sugar_config['lionixcrm']['workday_time_diff_holiday_table']['country'] = 'CR';
    $sugar_config['lionixcrm']['workday_time_diff_holiday_table']['utchourstimediff'] = '-6';
    $sugar_config['lionixcrm']['workday_time_diff_holiday_table']['starttime'] = '09:00';
    $sugar_config['lionixcrm']['workday_time_diff_holiday_table']['endtime'] = '18:00';
    $sugar_config['lionixcrm']['workday_time_diff_holiday_table']['starttimeweekend'] = '09:00';
    $sugar_config['lionixcrm']['workday_time_diff_holiday_table']['endtimeweekend'] = '12:00';
    ksort($sugar_config);
    write_array_to_file('sugar_config', $sugar_config, 'config.php');
    installLog('...LionixCRM added sugar_config values successfully.');
    // el fin
    $finmsg = 'LionixCRM install finished';
    installLog($finmsg);

    return $finmsg;
}
