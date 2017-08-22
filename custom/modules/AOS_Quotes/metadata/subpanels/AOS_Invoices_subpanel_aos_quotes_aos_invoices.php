<?php
// created: 2017-08-22 17:24:25
$subpanel_layout['list_fields'] = array (
  'quotenumber_c' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'vname' => 'LBL_QUOTENUMBER',
    'width' => '10%',
  ),
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '25%',
    'default' => true,
  ),
  'number' => 
  array (
    'width' => '5%',
    'vname' => 'LBL_LIST_NUM',
    'default' => true,
  ),
  'lxcode_c' => 
  array (
    'type' => 'int',
    'default' => true,
    'vname' => 'LBL_LXCODE',
    'width' => '10%',
  ),
  'billing_account' => 
  array (
    'width' => '20%',
    'vname' => 'LBL_BILLING_ACCOUNT',
    'default' => true,
  ),
  'total_amount' => 
  array (
    'type' => 'currency',
    'width' => '15%',
    'currency_format' => true,
    'vname' => 'LBL_GRAND_TOTAL',
    'default' => true,
  ),
  'expiration' => 
  array (
    'width' => '15%',
    'vname' => 'LBL_EXPIRATION',
    'default' => true,
  ),
  'assigned_user_name' => 
  array (
    'link' => 'assigned_user_link',
    'type' => 'relate',
    'vname' => 'LBL_ASSIGNED_USER',
    'width' => '15%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Users',
    'target_record_key' => 'assigned_user_id',
  ),
  'edit_button' => 
  array (
    'widget_class' => 'SubPanelEditButton',
    'module' => 'AOS_Quotes',
    'width' => '4%',
    'default' => true,
  ),
  'remove_button' => 
  array (
    'widget_class' => 'SubPanelRemoveButton',
    'module' => 'AOS_Quotes',
    'width' => '5%',
    'default' => true,
  ),
  'currency_id' => 
  array (
    'usage' => 'query_only',
  ),
);