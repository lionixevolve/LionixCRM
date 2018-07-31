<?php
$searchdefs ['Opportunities'] =
array (
  'layout' =>
  array (
    'basic_search' =>
    array (
      'name' =>
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'account_name' =>
      array (
        'name' => 'account_name',
        'default' => true,
        'width' => '10%',
      ),
      'current_user_only' =>
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'open_only' =>
      array (
        'name' => 'open_only',
        'label' => 'LBL_OPEN_ITEMS',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'favorites_only' =>
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'contact_fullname_cedula_search_nondb' =>
      array (
        'name' => 'contact_fullname_cedula_search_nondb',
        'vname' => 'LBL_CONTACT_FULLNAME_CEDULA_SEARCH_NONDB',
        'default' => true,
        'type' => 'name',
        'width' => '10%',
      ),
      'aos_quotes_quotenumber_c_search_nondb' =>
      array (
        'name' => 'aos_quotes_quotenumber_c_search_nondb',
        'vname' => 'LBL_AOS_QUOTES_QUOTENUMBER_C_SEARCH_NONDB',
        'default' => true,
        'type' => 'name',
        'width' => '10%',
      ),
    ),
    'advanced_search' =>
    array (
      'name' =>
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'account_name' =>
      array (
        'name' => 'account_name',
        'default' => true,
        'width' => '10%',
      ),
      'amount' =>
      array (
        'name' => 'amount',
        'default' => true,
        'width' => '10%',
      ),
      'assigned_user_id' =>
      array (
        'name' => 'assigned_user_id',
        'type' => 'enum',
        'label' => 'LBL_ASSIGNED_TO',
        'function' =>
        array (
          'name' => 'get_user_array',
          'params' =>
          array (
            0 => false,
          ),
        ),
        'default' => true,
        'width' => '10%',
      ),
      'sales_stage' =>
      array (
        'name' => 'sales_stage',
        'default' => true,
        'width' => '10%',
      ),
      'lead_source' =>
      array (
        'name' => 'lead_source',
        'default' => true,
        'width' => '10%',
      ),
      'date_closed' =>
      array (
        'name' => 'date_closed',
        'default' => true,
        'width' => '10%',
      ),
      'next_step' =>
      array (
        'type' => 'varchar',
        'label' => 'LBL_NEXT_STEP',
        'width' => '10%',
        'default' => true,
        'name' => 'next_step',
      ),
      'contact_fullname_cedula_search_nondb' =>
      array (
        'name' => 'contact_fullname_cedula_search_nondb',
        'vname' => 'LBL_CONTACT_FULLNAME_CEDULA_SEARCH_NONDB',
        'default' => true,
        'type' => 'name',
        'width' => '10%',
      ),
      'aos_quotes_quotenumber_c_search_nondb' =>
      array (
        'name' => 'aos_quotes_quotenumber_c_search_nondb',
        'vname' => 'LBL_AOS_QUOTES_QUOTENUMBER_C_SEARCH_NONDB',
        'default' => true,
        'type' => 'name',
        'width' => '10%',
      ),
      'lead_fullname_cedula_search_nondb' =>
      array (
        'name' => 'lead_fullname_cedula_search_nondb',
        'vname' => 'LBL_LEAD_FULLNAME_CEDULA_SEARCH_NONDB',
        'default' => true,
        'type' => 'name',
        'width' => '10%',
      ),
      'contract_name_search_nondb' =>
      array (
        'name' => 'contract_name_search_nondb',
        'vname' => 'LBL_CONTRACT_NAME_SEARCH_NONDB',
        'default' => true,
        'type' => 'name',
        'width' => '10%',
      ),
    ),
  ),
  'templateMeta' =>
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' =>
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
;
?>
