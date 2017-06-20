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
                array('id' => 'none-role', 'name' => 'None', 'description' => 'Read only access to all data.'),
                array('id' => 'all-role', 'name' => 'All', 'description' => 'Full access to all data.'),
                array('id' => 'owner-role', 'name' => 'Owner', 'description' => 'Partial access to some data.'),
                //agregar role de no mass update no export
                //explicar los números -99, 99, 75 etc
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
            case 'none-role':
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
                case 'owner-role':
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
    $queries_array = array(
        'custom/lionix/query/prospect_list/vista_cron_pl_daily_email_birthday_congratulations_contacts.sql',
        'custom/lionix/query/store_procedures/sp_infoticos.sql',
        'custom/lionix/query/store_functions/fn_create_holiday_table.sql',
        'custom/lionix/query/store_functions/fn_workday_time_diff_holiday_table.sql',
        'custom/lionix/query/install_scripts/install_routines.sql', //must be the last one to run
    );
    //TODO: call terminal with mysql database < file.sql
    foreach ($queries_array as $current_file) {
        if (file_exists($current_file)) {
            $query = file_get_contents($current_file);
            $db->query($query);
            if($current_file=='custom/lionix/query/install_scripts/install_routines.sql'){
                $db->query("call sp_install_routines");
                installLog("sp_install_routines executed");
            }
            installLog("...{$current_file} executed");
        }
    }
    installLog('...LionixCRM added custom store procedures and views into database successfully.');

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
    // $query = "INSERT INTO email_templates (id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, type) VALUES ('bda8bda8-bda8-bda8-bda8-bda8bda8bda8', utc_timestamp(), utc_timestamp(), '1', '1', 'off', 'z_daily_email_birthday_congratulations_template', 'This template is used to send automatically email congratulations to contacts on daily_email_birthday_congratulations_contacts target list.', 'Te deseamos un ¡Feliz cumpleaños!', '¡Muy feliz cumpleaños \$contact_first_name!', '<div class=\"mozaik-inner\" style=\"font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px 30px;margin:0px;\"><p style=\"text-align:left;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;\"><span style=\"font-family:tahoma, arial, helvetica, sans-serif;font-size:24px;line-height:38.4px;color:#444444;padding:0px;margin:0px;\">¡Muy feliz cumpleaños \$contact_first_name!</span></p><p style=\"font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;\"><img style=\"margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;\" src=\"custom/lionix/img/happy-birthday-to-you.jpg\" alt=\"happy-birthday-to-you.jpg\" width=\"842\" height=\"500\" /></p><p style=\"font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;\"></p><div class=\"mozaik-clear\" style=\"font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;height:0px;\"></div></div>', '0', '1', '0', 'campaign')
    // ";
    // $db->query($query);
    installLog('...LionixCRM added birthday email campaign (status=inactive) successfully.');
//     installLog('LionixCRM starting to add email templates only...');
// /*z_WF_NOT_OP_SALES_STAGE_CHANGE*/
// $query = "INSERT INTO email_templates
// (id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, `type`)
// VALUES('01abcdef-efef-efef-efef-efefefefefef', utc_timestamp(), utc_timestamp(), '1', '1', 'off', 'z_WF_NOT_OP_SALES_STAGE_CHANGE', NULL, NULL, ' La oportunidad$opportunity_name requiere tu atención Detalle:Nombre: $opportunity_nameHaz click en el nombre de la oportunidad para verla en LionixCRM', '<div class="mozaik-inner" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px 30px;margin:0px auto;width:1014px;background-color:rgb(250,250,250);"><table class="mce-item-table" style="width:100%;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:3px 3px 3px 0px;margin:0px;"><tbody style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;"><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;width:10%;"><img style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;" src="custom/themes/default/images/company_logo.png" alt="Alternativa - Furniture - Solutions" width="200" height="40" /></td><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;width:89%;"><h1 style="font-family:Arial, Helvetica, sans-serif;font-size:24px;line-height:38.4px;color:#444444;padding:0px;margin:0px;">La oportunidad $opportunity_name requiere tu atención</h1></td></tr></tbody></table><div class="mozaik-clear" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px;height:0px;"> <br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px auto;" /></div></div><div class="mozaik-inner" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px 30px;margin:0px auto;width:1014px;background-color:rgb(250,250,250);"><table style="width:100%;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:3px 3px 3px 0px;margin:0px;" class="mce-item-table"><tbody style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;"><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;width:49.0278%;"><h2 style="font-family:Arial, Helvetica, sans-serif;font-size:18px;line-height:28.8px;color:#444444;padding:0px;margin:0px;">Detalle</h2></td><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;width:49.9722%;"><h2 style="font-family:Arial, Helvetica, sans-serif;font-size:18px;line-height:28.8px;color:#444444;padding:0px;margin:0px;">Descripción</h2></td></tr><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;"><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;width:49.0278%;"><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;">Nombre: <a style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;" href="index.php?module=Opportunities&action=DetailView&record=$opportunity_id">$opportunity_name</a></p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;">Cuenta: <a style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;" href="index.php?module=Accounts&action=DetailView&record=$opportunity_account_id">$opportunity_account_name</a></p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;">Etapa de Ventas: $opportunity_sales_stage</p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;">Fecha de Creación: $opportunity_date_entered</p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;">Fecha de Cierre: $opportunity_date_closed</p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;">Asignado a: $opportunity_assigned_user_name</p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"> </p></td><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;width:49.9722%;">$opportunity_description</td></tr></tbody></table><div class="mozaik-clear" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;height:0px;"> </div></div><div class="mozaik-inner" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px 30px;margin:0px auto;width:1014px;background-color:rgb(250,250,250);"><p class="footer" style="font-family:Arial, Helvetica, sans-serif;font-size:10px;line-height:16px;color:#444444;padding:20px;margin:0px;">Haz click en el nombre de la oportunidad o cuenta para verla en LionixCRM<br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;" /></p><div class="mozaik-clear" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px;height:0px;"> <br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px auto;" /></div></div>', 0, '1', 0, NULL);
// ";
// $db->query($query);
//     installLog('...LionixCRM added email templates only successfully.');
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
