<?php
// Reference: https://community.sugarcrm.com/thread/29996-how-to-add-custom-dynamic-drop-down-field-based-on-query-options-list-report-module

function getDropdownOpportunitiesSalesStage()
{
    return array_merge(
        $GLOBALS['app_list_strings']['sales_stage_dom_oldvalues'],
        $GLOBALS['app_list_strings']['sales_stage_dom_b2b_goods'],
        $GLOBALS['app_list_strings']['sales_stage_dom_b2b_services'],
        $GLOBALS['app_list_strings']['sales_stage_dom_b2c_goods'],
        $GLOBALS['app_list_strings']['sales_stage_dom_b2c_services']
    );
}
