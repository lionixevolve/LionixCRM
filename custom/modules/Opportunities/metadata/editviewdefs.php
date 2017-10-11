<?php
$viewdefs ['Opportunities'] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'javascript' => '{$PROBABILITY_SCRIPT}',
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ASSIGNMENT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'name',
          ),
          1 => 'account_name',
        ),
        1 => 
        array (
          0 => 'sales_stage',
          1 => 'probability',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'currency_id',
            'label' => 'LBL_CURRENCY',
          ),
          1 => 
          array (
            'name' => 'maincontact_c',
            'studio' => 'visible',
            'label' => 'LBL_MAINCONTACT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'maincontactcedula_c',
            'label' => 'LBL_MAINCONTACTCEDULA',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'maincontactfirstname_c',
            'label' => 'LBL_MAINCONTACTFIRSTNAME',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'maincontactlastname_c',
            'label' => 'LBL_MAINCONTACTLASTNAME',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'maincontactlastname2_c',
            'label' => 'LBL_MAINCONTACTLASTNAME2',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'maincontactphonework_c',
            'label' => 'LBL_MAINCONTACTPHONEWORK',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'maincontactemailaddress_c',
            'label' => 'LBL_MAINCONTACTEMAILADDRESS',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'maincontacttitle_c',
            'label' => 'LBL_MAINCONTACTTITLE',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'amount',
          ),
          1 => 
          array (
            'name' => 'date_closed',
          ),
        ),
        11 => 
        array (
          0 => 'campaign_name',
          1 => 
          array (
            'name' => 'dnc_c',
            'label' => 'LBL_DNC',
          ),
        ),
        12 => 
        array (
          0 => 'next_step',
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'fpurchaseorder_c',
            'label' => 'LBL_FPURCHASEORDER',
          ),
        ),
        14 => 
        array (
          0 => 'description',
        ),
        15 => 
        array (
          0 => 
          array (
            'name' => 'chat_c',
            'studio' => 'visible',
            'label' => 'LBL_CHAT',
          ),
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' => 
      array (
        0 => 
        array (
          0 => 'assigned_user_name',
        ),
      ),
    ),
  ),
);
$viewdefs['Opportunities']['EditView']['templateMeta'] = array (
  'maxColumns' => '2',
  'widths' => 
  array (
    0 => 
    array (
      'label' => '10',
      'field' => '30',
    ),
    1 => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
  'javascript' => '{$PROBABILITY_SCRIPT}',
);
?>
