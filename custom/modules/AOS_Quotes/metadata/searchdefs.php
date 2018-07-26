<?php
$module_name = 'AOS_Quotes';
$_module_name = 'aos_quotes';
$searchdefs [$module_name] =
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
      'current_user_only' =>
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
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
      'product_part_number_nondb' =>
      array (
        'name' => 'product_part_number_search_nondb',
        'vname' => 'LBL_PRODUCT_PART_NUMBER_SEARCH_NONDB',
        'default' => true,
        'type' => 'name',
        'width' => '10%',
      ),
      'product_name_nondb' =>
      array (
        'name' => 'product_name_search_nondb',
        'vname' => 'LBL_PRODUCT_NAME_SEARCH_NONDB',
        'default' => true,
        'type' => 'name',
        'width' => '10%',
      ),
    ),
    'advanced_search' =>
    array (
      0 => 'name',
      1 => 'billing_contact',
      2 => 'billing_account',
      3 => 'number',
      4 => 'total_amount',
      5 => 'expiration',
      6 => 'stage',
      7 => 'term',
      8 =>
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
      ),
      'product_part_number_nondb' =>
      array (
        'name' => 'product_part_number_search_nondb',
        'vname' => 'LBL_PRODUCT_PART_NUMBER_SEARCH_NONDB',
        'default' => true,
        'type' => 'name',
        'width' => '10%',
      ),
      'product_name_nondb' =>
      array (
        'name' => 'product_name_search_nondb',
        'vname' => 'LBL_PRODUCT_NAME_SEARCH_NONDB',
        'default' => true,
        'type' => 'name',
        'width' => '10%',
      ),
    ),
  ),
  'templateMeta' =>
  array (
    'maxColumns' => '3',
    'widths' =>
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
;
?>
