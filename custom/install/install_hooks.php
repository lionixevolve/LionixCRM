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
    // view actions (5)
    // view all            90
    // view group          80
    // view owner          75
    // view not set         0
    // view none          -99
    $roles = array(
                array('id' => 'all-role',              'name' => 'All',               'description' => 'Full access to all data.'),
                array('id' => 'read-only-role',        'name' => 'Read Only',         'description' => 'Read only access to all data.'),
                array('id' => 'delete-no-role',        'name' => 'No Delete',         'description' => 'Cannot deleted any data.'),
                array('id' => 'delete-owner-role',     'name' => 'Owner Delete',      'description' => 'Delete only my records.'),
                array('id' => 'delete-group-role',     'name' => 'Group Delete',      'description' => 'Delete only group records.'),
                array('id' => 'edit-no-role',          'name' => 'No Edit',           'description' => 'Cannot edit any data.'),
                array('id' => 'edit-owner-role',       'name' => 'Owner Edit',        'description' => 'Edit only my records.'),
                array('id' => 'edit-group-role',       'name' => 'Group Edit',        'description' => 'Edit only group records.'),
                array('id' => 'export-no-role',        'name' => 'No Export',         'description' => 'Cannot export any data.'),
                array('id' => 'export-owner-role',     'name' => 'Owner Export',      'description' => 'Export only my records.'),
                array('id' => 'export-group-role',     'name' => 'Group Export',      'description' => 'Export only group records.'),
                array('id' => 'import-no-role',        'name' => 'No Import',         'description' => 'Cannot import any data.'),
                array('id' => 'import-owner-role',     'name' => 'Owner Import',      'description' => 'Import only my records.'),
                array('id' => 'import-group-role',     'name' => 'Group Import',      'description' => 'Import only group records.'),
                array('id' => 'list-no-role',          'name' => 'No List',           'description' => 'Cannot list any data.'),
                array('id' => 'list-owner-role',       'name' => 'Owner List',        'description' => 'List only my records.'),
                array('id' => 'list-group-role',       'name' => 'Group List',        'description' => 'List only group records.'),
                array('id' => 'massupdate-no-role',    'name' => 'No Mass Update',    'description' => 'Cannot mass update any data.'),
                array('id' => 'massupdate-owner-role', 'name' => 'Owner Mass Update', 'description' => 'Mass update only my records.'),
                array('id' => 'massupdate-group-role', 'name' => 'Group Mass Update', 'description' => 'Mass update only group records.'),
                array('id' => 'view-no-role',          'name' => 'No View',           'description' => 'Cannot view any data.'),
                array('id' => 'view-owner-role',       'name' => 'Owner View',        'description' => 'View only my records.'),
                array('id' => 'view-group-role',       'name' => 'Group View',        'description' => 'View only group records.'),
            );
    foreach ($roles as $role) {
        $query = "
            INSERT INTO acl_roles (`id`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `name`, `description`, `deleted`)
            VALUES ('{$role['id']}', utc_timestamp(), utc_timestamp(), '1', '1', '{$role['name']}', '{$role['description']}', '0')
        ";
        $db->query($query);
        $query = "
            -- This insert creates 'all-role' too
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
            case 'read-only-role':
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
            case 'delete-no-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '-99' -- -99 is none
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('delete'))
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
                                             'edit',
                                             'export',
                                             'import',
                                             'list',
                                             'massupdate',
                                             'view'))
                ";
                $db->query($query);
            break;
            case 'delete-owner-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '75' -- 75 is owner
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('delete'))
                ";
                $db->query($query);
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '0' -- 0 is not set
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('access',
                                            'edit',
                                            'export',
                                            'import',
                                            'list',
                                            'massupdate',
                                            'view'))
                ";
                $db->query($query);
            break;
            case 'delete-group-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '80' -- 80 is group
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('delete'))
                ";
                $db->query($query);
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '0' -- 0 is not set
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('access',
                                            'edit',
                                            'export',
                                            'import',
                                            'list',
                                            'massupdate',
                                            'view'))
                ";
                $db->query($query);
            break;
            case 'edit-no-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '-99' -- -99 is none
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('edit'))
                ";
                $db->query($query);
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '0' -- 0 is not set
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('access',
                                            'delete',
                                            'export',
                                            'import',
                                            'list',
                                            'massupdate',
                                            'view'))
                ";
                $db->query($query);
            break;
            case 'edit-owner-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '75' -- 75 is owner
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('edit'))
                ";
                $db->query($query);
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '0' -- 0 is not set
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('access',
                                            'delete',
                                            'export',
                                            'import',
                                            'list',
                                            'massupdate',
                                            'view'))
                ";
                $db->query($query);
            break;
            case 'edit-group-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '80' -- 80 is group
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('edit'))
                ";
                $db->query($query);
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '0' -- 0 is not set
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('access',
                                            'delete',
                                            'export',
                                            'import',
                                            'list',
                                            'massupdate',
                                            'view'))
                ";
                $db->query($query);
            break;
            case 'export-no-role':
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
                                             'import',
                                             'list',
                                             'massupdate',
                                             'view'))
                ";
                $db->query($query);
            break;
            case 'export-owner-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '75' -- 75 is owner
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
                                             'import',
                                             'list',
                                             'massupdate',
                                             'view'))
                ";
                $db->query($query);
            break;
            case 'export-group-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '80' -- 80 is group
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
                                             'import',
                                             'list',
                                             'massupdate',
                                             'view'))
                ";
                $db->query($query);
            break;
            case 'import-no-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '-99' -- -99 is none
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('import'))
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
                                             'list',
                                             'massupdate',
                                             'view'))
                ";
                $db->query($query);
            break;
            case 'import-owner-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '75' -- 75 is owner
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('import'))
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
                                             'list',
                                             'massupdate',
                                             'view'))
                ";
                $db->query($query);
            break;
            case 'import-group-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '80' -- 80 is group
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('import'))
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
                                             'list',
                                             'massupdate',
                                             'view'))
                ";
                $db->query($query);
            break;
            case 'list-no-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '-99' -- -99 is none
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('list'))
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
                                             'massupdate',
                                             'view'))
                ";
                $db->query($query);
            break;
            case 'list-owner-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '75' -- 75 is owner
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('list'))
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
                                             'massupdate',
                                             'view'))
                ";
                $db->query($query);
            break;
            case 'list-group-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '80' -- 80 is group
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('list'))
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
                                             'massupdate',
                                             'view'))
                ";
                $db->query($query);
            break;
            case 'massupdate-no-role':
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
            case 'massupdate-owner-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '75' -- 75 is owner
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
            case 'massupdate-group-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '80' -- 80 is group
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
            case 'view-no-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '-99' -- -99 is none
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('view'))
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
                                             'massupdate'))
                ";
                $db->query($query);
            break;
            case 'view-owner-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '75' -- 75 is owner
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('view'))
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
                                             'massupdate'))
                ";
                $db->query($query);
            break;
            case 'view-group-role':
                $query = "
                    UPDATE acl_roles_actions
                    SET access_override = '80' -- 80 is group
                    WHERE role_id = '{$role['id']}'
                        AND action_id IN
                            (SELECT id
                             FROM acl_actions
                             WHERE name IN ('view'))
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
                                             'massupdate'))
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
        // Notes
        array(
            'module'         => 'Notes',
            'hook'           => 'before_save',
            'order'          => 101,
            'description'    => 'saveFetchedRowBS',
            'file'           => 'custom/modules/Notes/logic_hooks_before_and_after_save.php',
            'class'          => 'LXNotesBeforeAndAfterSaveMethods',
            'function'       => 'saveFetchedRowBS',
        ),
        array(
            'module'         => 'Notes',
            'hook'           => 'before_save',
            'order'          => 102,
            'description'    => 'setContactIdWhenRelatedToIsContactsBS',
            'file'           => 'custom/modules/Notes/logic_hooks_before_and_after_save.php',
            'class'          => 'LXNotesBeforeAndAfterSaveMethods',
            'function'       => 'setContactIdWhenRelatedToIsContactsBS',
        ),
        // Accounts
        array(
            'module'         => 'Accounts',
            'hook'           => 'after_save',
            'order'          => 102,
            'description'    => 'setMissingNotesLinksAS',
            'file'           => 'custom/modules/Accounts/logic_hooks_before_and_after_save.php',
            'class'          => 'LXAccountsBeforeAndAfterSaveMethods',
            'function'       => 'setMissingNotesLinksAS',
        ),
        array(
            'module'         => 'Accounts',
            'hook'           => 'after_retrieve',
            'order'          => 102,
            'description'    => 'setLinksToUploadedFiles',
            'file'           => 'custom/modules/Accounts/logic_hooks_after_retrieve.php',
            'class'          => 'LXAccountsAfterRetrieveMethods',
            'function'       => 'setLinksToUploadedFiles',
        ),
        // Contacts
        array(
            'module'         => 'Contacts',
            'hook'           => 'after_save',
            'order'          => 102,
            'description'    => 'setMissingNotesLinksAS',
            'file'           => 'custom/modules/Contacts/logic_hooks_before_and_after_save.php',
            'class'          => 'LXContactsBeforeAndAfterSaveMethods',
            'function'       => 'setMissingNotesLinksAS',
        ),
        array(
            'module'         => 'Contacts',
            'hook'           => 'after_retrieve',
            'order'          => 102,
            'description'    => 'setLinksToUploadedFiles',
            'file'           => 'custom/modules/Contacts/logic_hooks_after_retrieve.php',
            'class'          => 'LXContactsAfterRetrieveMethods',
            'function'       => 'setLinksToUploadedFiles',
        ),
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
            'hook'           => 'before_save',
            'order'          => 102,
            'description'    => 'setStatusCBS',
            'file'           => 'custom/modules/Opportunities/logic_hooks_before_and_after_save.php',
            'class'          => 'LXOpportunitiesBeforeAndAfterSaveMethods',
            'function'       => 'setStatusCBS',
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
            'hook'           => 'after_save',
            'order'          => 102,
            'description'    => 'setPreviousSalesStageCAS',
            'file'           => 'custom/modules/Opportunities/logic_hooks_before_and_after_save.php',
            'class'          => 'LXOpportunitiesBeforeAndAfterSaveMethods',
            'function'       => 'setPreviousSalesStageCAS',
        ),
        array(
            'module'         => 'Opportunities',
            'hook'           => 'after_save',
            'order'          => 103,
            'description'    => 'setMissingNotesLinksAS',
            'file'           => 'custom/modules/Opportunities/logic_hooks_before_and_after_save.php',
            'class'          => 'LXOpportunitiesBeforeAndAfterSaveMethods',
            'function'       => 'setMissingNotesLinksAS',
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
        array(
            'module'         => 'Opportunities',
            'hook'           => 'after_retrieve',
            'order'          => 102,
            'description'    => 'setLinksToUploadedFiles',
            'file'           => 'custom/modules/Opportunities/logic_hooks_after_retrieve.php',
            'class'          => 'LXOpportunitiesAfterRetrieveMethods',
            'function'       => 'setLinksToUploadedFiles',
        ),
        // AOS_Quotes
        array(
            'module'         => 'AOS_Quotes',
            'hook'           => 'before_save',
            'order'          => 101,
            'description'    => 'saveFetchedRowBS',
            'file'           => 'custom/modules/AOS_Quotes/logic_hooks_before_and_after_save.php',
            'class'          => 'LXAOSQuotesBeforeAndAfterSaveMethods',
            'function'       => 'saveFetchedRowBS',
        ),
        array(
            'module'         => 'AOS_Quotes',
            'hook'           => 'before_save',
            'order'          => 102,
            'description'    => 'setNumberBS',
            'file'           => 'custom/modules/AOS_Quotes/logic_hooks_before_and_after_save.php',
            'class'          => 'LXAOSQuotesBeforeAndAfterSaveMethods',
            'function'       => 'setNumberBS',
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
    $sugar_config['lionixcrm']['environment'] = 'production'; //The options for environment are 'testing' or 'production'
    $sugar_config['lionixcrm']['business_type'] = 'b2b'; //The options for business_type are 'b2b' or 'b2c', if null or '' it uses default CRM validation rules
    $sugar_config['lionixcrm']['modules']['opportunities']['exclude_fields_for_update_sales_stages_time_in_mins'] = array(
        'ssclosedwoninmins_c',
        //'anyOtherField'
    );
    $sugar_config['lionixcrm']['modules']['opportunities']['upload_files_fields'] = array(
        array(
            'field_name' => 'fpurchaseorder_c',
            'button_label'=> 'Orden de compra',
            'ok_message'=> 'Orden de compra agregada correctamente.',
        ),
    );
    $sugar_config['lionixcrm']['modules']['accounts']['upload_files_fields'] = array(
        // array(
            // 'field_name' => 'f{name}_c',
            // 'button_label'=> '{label}',
            // 'ok_message'=> '{any success message}',
        // ),
    );
    $sugar_config['lionixcrm']['modules']['contacts']['upload_files_fields'] = array(
        // array(
            // 'field_name' => 'f{name}_c',
            // 'button_label'=> '{label}',
            // 'ok_message'=> '{any success message}',
        // ),
    );
    $sugar_config['lionixcrm']['smartchat'] = array(
        'chat_c',
        //'anyOtherField',
    );
    $sugar_config['email_xss'] = 'YToxMzp7czo2OiJhcHBsZXQiO3M6NjoiYXBwbGV0IjtzOjQ6ImJhc2UiO3M6NDoiYmFzZSI7czo1OiJlbWJlZCI7czo1OiJlbWJlZCI7czo0OiJmb3JtIjtzOjQ6ImZvcm0iO3M6NToiZnJhbWUiO3M6NToiZnJhbWUiO3M6ODoiZnJhbWVzZXQiO3M6ODoiZnJhbWVzZXQiO3M6NjoiaWZyYW1lIjtzOjY6ImlmcmFtZSI7czo2OiJpbXBvcnQiO3M6ODoiXD9pbXBvcnQiO3M6NToibGF5ZXIiO3M6NToibGF5ZXIiO3M6NDoibGluayI7czo0OiJsaW5rIjtzOjY6Im9iamVjdCI7czo2OiJvYmplY3QiO3M6MzoieG1wIjtzOjM6InhtcCI7czo2OiJzY3JpcHQiO3M6Njoic2NyaXB0Ijt9';
    $sugar_config['dbconfigoption']['collation'] = 'utf8_general_ci';
    $sugar_config['default_currency_iso4217'] = 'CRC';
    $sugar_config['default_currency_name'] = 'Colones';
    $sugar_config['default_currency_symbol'] = '₡';
    $sugar_config['disable_persistent_connections'] = false;
    $sugar_config['aos']['quotes']['initialNumber'] = '1';
    $sugar_config['aos']['invoices']['initialNumber'] = '1';
    $sugar_config['cron']['allowed_cron_users'] = array(0 => 'qma',1 => 'www-data',2 => 'apache');
    ksort($sugar_config);
    write_array_to_file('sugar_config', $sugar_config, 'config.php');
    installLog('...LionixCRM added sugar_config values successfully.');
    // el fin
    $finmsg = 'LionixCRM install finished';
    installLog($finmsg);

    return $finmsg;
}
