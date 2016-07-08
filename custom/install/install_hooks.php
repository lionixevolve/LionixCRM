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
    // el fin
    $finmsg = 'LionixCRM install finished';
    installLog($finmsg);

    return $finmsg;
}
