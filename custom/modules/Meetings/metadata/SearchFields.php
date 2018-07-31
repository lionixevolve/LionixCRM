<?php
// created: 2018-07-31 17:53:29
$searchFields['Meetings'] = array (
  'name' =>
  array (
    'query_type' => 'default',
  ),
  'contact_name' =>
  array (
    'query_type' => 'default',
    'db_field' =>
    array (
      0 => 'contacts.first_name',
      1 => 'contacts.last_name',
    ),
  ),
  'date_start' =>
  array (
    'query_type' => 'default',
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
  'status' =>
  array (
    'query_type' => 'default',
    'options' => 'meeting_status_dom',
    'template_var' => 'STATUS_FILTER',
  ),
  'open_only' =>
  array (
    'query_type' => 'default',
    'db_field' =>
    array (
      0 => 'status',
    ),
    'operator' => 'not in',
    'closed_values' =>
    array (
      0 => 'Held',
      1 => 'Not Held',
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
			                        and favorites.parent_type = \'Meetings\'
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
  'range_date_start' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_start' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_start' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_date_end' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_end' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_end' =>
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
        SELECT meeting_id
        FROM (
        ###
            SELECT
            me.id AS meeting_id
            ,CONCAT(
                IFNULL(c.first_name,''),' ',IFNULL(c.last_name,''),' ',IFNULL(cc.lastname2_c,''),' ',
                IFNULL(cc.cedula_c,''),' ',
                IFNULL(c.phone_fax,''),' ',IFNULL(c.phone_home,''),' ',IFNULL(c.phone_mobile,''),' ',IFNULL(c.phone_other,''),' ',IFNULL(c.phone_work,'')
            ) AS contact_full_name_cedula_and_phone
            FROM contacts c
            LEFT JOIN contacts_cstm cc ON c.id = cc.id_c
            LEFT JOIN meetings_contacts meco ON c.id = meco.contact_id
            LEFT JOIN meetings me ON meco.meeting_id = me.id
            WHERE c.deleted = 0
            AND meco.deleted = 0
            AND me.deleted = 0
            UNION
            SELECT
            me.id AS meeting_id
            ,CONCAT(
                IFNULL(c.first_name,''),' ',IFNULL(c.last_name,''),' ',IFNULL(cc.lastname2_c,''),' ',
                IFNULL(cc.cedula_c,''),' ',
                IFNULL(c.phone_fax,''),' ',IFNULL(c.phone_home,''),' ',IFNULL(c.phone_mobile,''),' ',IFNULL(c.phone_other,''),' ',IFNULL(c.phone_work,'')
            ) AS contact_full_name_cedula_and_phone
            FROM contacts c
            LEFT JOIN contacts_cstm cc ON c.id = cc.id_c
            LEFT JOIN meetings me ON c.id = me.parent_id AND me.parent_type = 'Contacts'
            WHERE c.deleted = 0
            AND me.deleted = 0
        ###
        ) t
        WHERE t.contact_full_name_cedula_and_phone LIKE
    ",
    'db_field' =>
    array (
      0 => 'id',
    ),
  ),
);
