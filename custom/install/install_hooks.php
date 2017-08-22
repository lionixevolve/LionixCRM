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
    // All posible values for each action
    // access actions (only 3)
    // access enable       89
    // access disabled    -98
    // access not set       0
    // delete actions (5)
    // delete all          90
    // delete group        80
    // delete owner        75
    // delete not set       0
    // delete none        -99
    // edit actions (5)
    // edit all            90
    // edit group          80
    // edit owner          75
    // edit not set         0
    // edit none          -99
    // export actions (5)
    // export all          90
    // export group        80
    // export owner        75
    // export not set       0
    // export none        -99
    // import actions (only 3)
    // import all          90
    // import not set       0
    // import none        -99
    // list actions (5)
    // list all            90
    // list group          80
    // list owner          75
    // list not set         0
    // list none          -99
    // massupdate actions (only 3)
    // massupdate all      90
    // massupdate not set   0
    // massupdate none    -99
    // list actions (5)
    // list all            90
    // list group          80
    // list owner          75
    // list not set         0
    // list none          -99
    $roles = array(
                array('id' => 'none-role', 'name' => 'None', 'description' => 'Read only access to all data.'),
                array('id' => 'all-role', 'name' => 'All', 'description' => 'Full access to all data.'),
                array('id' => 'owner-role', 'name' => 'Owner', 'description' => 'Partial access to some data.'),
                array('id' => 'no-massupdate-role', 'name' => 'NoMassUpdate', 'description' => 'Cannot MassUpdate any data.'),
                array('id' => 'no-export-role', 'name' => 'NoExport', 'description' => 'Cannot export any data.'),
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
                    SET access_override = '89' -- 89 is enable
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('access'))
                ";
                $db->query($query);
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '-99' -- -99 is none
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
                    SET access_override = '90' -- 90 is all
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
                    SET access_override = '89' -- 89 is enable
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('access'))
                ";
                $db->query($query);
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '-99' -- -99 is none
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
                    SET access_override = '75' -- 75 is owner
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
                    SET access_override = '90' -- 90 is all
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
            case 'no-massupdate-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '-99' -- -99 is none
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('massupdate'))
                ";
                $db->query($query);
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '0' -- 0 is not set
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ( 'access',
                                             'delete',
                                             'edit',
                                             'export',
                                             'import',
                                             'list',
                                             'view'))
                ";
                $db->query($query);
            break;
            case 'no-export-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '-99' -- -99 is none
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('export'))
                ";
                $db->query($query);
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '0' -- 0 is not set
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ( 'access',
                                             'delete',
                                             'edit',
                                             'massupdate',
                                             'import',
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
            if ($current_file=='custom/lionix/query/install_scripts/install_routines.sql') {
                $db->query("call sp_install_routines");
                installLog("sp_install_routines executed");
            }
            installLog("...{$current_file} executed");
        }
    }
    installLog('...LionixCRM added custom store procedures and views into database successfully.');

    // lxcode_c
    installLog('LionixCRM starting to add lxcode_c to all custom and audit tables...');
    $query = "
        SELECT TABLE_NAME,
               IF(TABLE_NAME LIKE '%_cstm',REPLACE(REPLACE(REPLACE(CONCAT(UPPER(MID(TABLE_NAME, 1, 1)),MID(REPLACE(TABLE_NAME, '_cstm', ''), 2)),'_lists','Lists'),'Aos_i','AOS_I'),'Aos_q','AOS_Q'),'') AS 'MODULE_NAME'
        FROM information_schema.tables
        WHERE table_schema = '{$database}'
            AND (TABLE_NAME LIKE '%cstm'
                 OR TABLE_NAME LIKE '%audit')
    ";
    $result = $db->query($query);
    while (($row = $db->fetchByAssoc($result)) != null) {
        $query = "ALTER TABLE {$row['TABLE_NAME']} ADD lxcode_c int AUTO_INCREMENT NOT NULL UNIQUE";
        $db->query($query);
        if (!empty($row['MODULE_NAME'])) {
            $query = "INSERT INTO `fields_meta_data` (`id`, `name`, `vname`, `custom_module`, `type`, `len`, `required`, `date_modified`, `deleted`, `audited`, `massupdate`, `duplicate_merge`, `reportable`, `importable`, `ext3`) VALUES ('{$row['MODULE_NAME']}lxcode_c', 'lxcode_c', 'LBL_LXCODE', '{$row['MODULE_NAME']}', 'int', '255', '0', utc_timestamp(), '0', '0', '0', '0', '1', 'false', '1')";
            $db->query($query);
        }
    }
    installLog('...LionixCRM added lxcode_c to all custom and audit tables successfully.');
    installLog('LionixCRM starting to add custom Logic Hooks ...');
    $hooks= array(
        // Opportunities
        array(
            'module'         => 'Opportunities',
            'hook'           => 'before_save',
            'order'          => 101,
            'description'    => 'saveFetchedRowBS',
            'file'           => 'custom/modules/Opportunities/logic_hooks_before_and_after_save.php',
            'class'          => 'LXOpportunitiesBeforeAndAfterSaveMethods',
            'function'       => 'saveFetchedRowBS',
        ),
        array(
            'module'         => 'Opportunities',
            'hook'           => 'after_save',
            'order'          => 101,
            'description'    => 'setMainContactCAS',
            'file'           => 'custom/modules/Opportunities/logic_hooks_before_and_after_save.php',
            'class'          => 'LXOpportunitiesBeforeAndAfterSaveMethods',
            'function'       => 'setMainContactCAS',
        ),
        array(
            'module'         => 'Opportunities',
            'hook'           => 'after_retrieve',
            'order'          => 101,
            'description'    => 'setMainContactC',
            'file'           => 'custom/modules/Opportunities/logic_hooks_after_retrieve.php',
            'class'          => 'LXOpportunitiesAfterRetrieveMethods',
            'function'       => 'setMainContactC',
        ),
    );
    foreach ($hooks as $hook) {
        check_logic_hook_file($hook['module'], $hook['hook'], array($hook['order'], $hook['description'],  $hook['file'], $hook['class'], $hook['function']));
    }
    installLog('...LionixCRM added custom Logic Hooks successfully.');
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
    $sugar_config['lionixcrm']['business_type'] = 'b2b';
    $sugar_config['email_xss'] = 'YToxMzp7czo2OiJhcHBsZXQiO3M6NjoiYXBwbGV0IjtzOjQ6ImJhc2UiO3M6NDoiYmFzZSI7czo1OiJlbWJlZCI7czo1OiJlbWJlZCI7czo0OiJmb3JtIjtzOjQ6ImZvcm0iO3M6NToiZnJhbWUiO3M6NToiZnJhbWUiO3M6ODoiZnJhbWVzZXQiO3M6ODoiZnJhbWVzZXQiO3M6NjoiaWZyYW1lIjtzOjY6ImlmcmFtZSI7czo2OiJpbXBvcnQiO3M6ODoiXD9pbXBvcnQiO3M6NToibGF5ZXIiO3M6NToibGF5ZXIiO3M6NDoibGluayI7czo0OiJsaW5rIjtzOjY6Im9iamVjdCI7czo2OiJvYmplY3QiO3M6MzoieG1wIjtzOjM6InhtcCI7czo2OiJzY3JpcHQiO3M6Njoic2NyaXB0Ijt9';
    $sugar_config['dbconfigoption']['collation'] = 'utf8_general_ci';
    $sugar_config['default_currency_iso4217'] = 'CRC';
    $sugar_config['default_currency_name'] = 'Colones';
    $sugar_config['default_currency_symbol'] = '₡';
    $sugar_config['disable_persistent_connections'] = false;
    $sugar_config['aos']['quotes']['initialNumber'] = date("Y").'000';
    $sugar_config['cron']['allowed_cron_users'] = array(0 => 'qma',1 => 'www-data',2 => 'apache');
    ksort($sugar_config);
    write_array_to_file('sugar_config', $sugar_config, 'config.php');
    installLog('...LionixCRM added sugar_config values successfully.');
    // el fin
    $finmsg = 'LionixCRM install finished';
    installLog($finmsg);

    return $finmsg;
}
