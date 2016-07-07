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
        $result = $db->query($query);
    }
    installLog('...LionixCRM added roles successfully.');

    installLog('LionixCRM install finished');

    return 'LionixCRM install finished';
}
