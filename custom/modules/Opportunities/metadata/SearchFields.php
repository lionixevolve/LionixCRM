<?php
// created: 2018-07-26 19:20:41
$searchFields['Opportunities'] = array (
  'name' =>
  array (
    'query_type' => 'default',
  ),
  'account_name' =>
  array (
    'query_type' => 'default',
    'db_field' =>
    array (
      0 => 'accounts.name',
    ),
  ),
  'amount' =>
  array (
    'query_type' => 'default',
  ),
  'next_step' =>
  array (
    'query_type' => 'default',
  ),
  'probability' =>
  array (
    'query_type' => 'default',
  ),
  'lead_source' =>
  array (
    'query_type' => 'default',
    'operator' => '=',
    'options' => 'lead_source_dom',
    'template_var' => 'LEAD_SOURCE_OPTIONS',
  ),
  'opportunity_type' =>
  array (
    'query_type' => 'default',
    'operator' => '=',
    'options' => 'opportunity_type_dom',
    'template_var' => 'TYPE_OPTIONS',
  ),
  'sales_stage' =>
  array (
    'query_type' => 'default',
    'operator' => '=',
    'options' => 'sales_stage_dom',
    'template_var' => 'SALES_STAGE_OPTIONS',
    'options_add_blank' => true,
  ),
  'current_user_only' =>
  array (
    'query_type' => 'default',
    'db_field' =>
    array (
      0 => 'assigned_user_id',
    ),
    'my_items' => true,
    'vname' => 'LBL_CURRENT_USER_FILTER',
    'type' => 'bool',
  ),
  'assigned_user_id' =>
  array (
    'query_type' => 'default',
  ),
  'open_only' =>
  array (
    'query_type' => 'default',
    'db_field' =>
    array (
      0 => 'sales_stage',
    ),
    'operator' => 'not in',
    'closed_values' =>
    array (
      0 => 'Closed Won',
      1 => 'Closed Lost',
    ),
    'type' => 'bool',
  ),
  'favorites_only' =>
  array (
    'query_type' => 'format',
    'operator' => 'subquery',
    'checked_only' => true,
    'subquery' => 'SELECT favorites.parent_id FROM favorites
			                    WHERE favorites.deleted = 0
			                        and favorites.parent_type = \'Opportunities\'
			                        and favorites.assigned_user_id = \'{1}\'',
    'db_field' =>
    array (
      0 => 'id',
    ),
  ),
  'range_date_entered' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_entered' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_entered' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_date_modified' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_modified' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_modified' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_date_closed' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_closed' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_closed' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_amount' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_amount' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_amount' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_dateinvoiced' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_dateinvoiced' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_dateinvoiced' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_datepaid' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_datepaid' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_datepaid' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_dnc' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_dnc' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_dnc' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_datepaid_c' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_datepaid_c' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_datepaid_c' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_lxcode_c' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_lxcode_c' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_lxcode_c' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_dateinvoiced_c' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_dateinvoiced_c' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_dateinvoiced_c' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'contact_fullname_cedula_search_nondb' =>
  array (
    'query_type' => 'default',
    'operator' => 'subquery',
    'subquery' => "
        SELECT o.id
        FROM contacts c
        LEFT JOIN contacts_cstm cc ON c.id = cc.id_c
        LEFT JOIN opportunities_contacts oc ON c.id = oc.contact_id
        LEFT JOIN opportunities o ON oc.opportunity_id = o.id
        WHERE c.deleted = 0
        AND oc.deleted = 0
        AND o.deleted = 0
        AND CONCAT(IFNULL(c.first_name,''),' ',IFNULL(c.last_name,''),' ',IFNULL(cc.lastname2_c,''),' ',IFNULL(cc.cedula_c,'')) LIKE
    ",
    'db_field' =>
    array (
      0 => 'id',
    ),
  ),
);
