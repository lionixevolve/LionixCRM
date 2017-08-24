<?php
$viewdefs ['Opportunities'] = 
array (
  'QuickCreate' => 
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
            'displayParams' => 
            array (
              'required' => true,
            ),
          ),
          1 => 
          array (
            'name' => 'account_name',
          ),
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
            'name' => 'maincontactfirstname_c',
            'label' => 'LBL_MAINCONTACTFIRSTNAME',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'maincontactlastname_c',
            'label' => 'LBL_MAINCONTACTLASTNAME',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'maincontactlastname2_c',
            'label' => 'LBL_MAINCONTACTLASTNAME2',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'maincontactphonework_c',
            'label' => 'LBL_MAINCONTACTPHONEWORK',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'maincontactemailaddress_c',
            'label' => 'LBL_MAINCONTACTEMAILADDRESS',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'maincontacttitle_c',
            'label' => 'LBL_MAINCONTACTTITLE',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'maincontactcedula_c',
            'label' => 'LBL_MAINCONTACTCEDULA',
          ),
        ),
        10 => 
        array (
          0 => 'amount',
          1 => 'date_closed',
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'dnc_c',
            'label' => 'LBL_DNC',
          ),
          1 => 
          array (
            'name' => 'campaign_name',
            'label' => 'LBL_CAMPAIGN',
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
            'name' => 'description',
            'comment' => 'Full text of the note',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
          ),
        ),
      ),
    ),
  ),
);
$viewdefs['Opportunities']['QuickCreate']['templateMeta'] = array (
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
