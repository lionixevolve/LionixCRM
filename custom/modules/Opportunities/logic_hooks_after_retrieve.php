<?php

class LXOpportunitiesAfterRetrieveMethods
{
    public function setMainContactC(&$bean, $event, $arguments)
    {
        $bean->custom_fields->retrieve();
        if ($_REQUEST['module'] == 'Opportunities' && $_REQUEST['action'] == 'DetailView') {
            $query = "
                select trim(concat(ifnull(first_name,''), ' ',ifnull(last_name,''))) AS 'maincontact_c'
                from contacts
                where nivel_precio = '{$bean->maincontact_c}'
            ";
            $results = $db->query($query);
            if ($row = $db->fetchByAssoc($results)) {
                $bean->maincontact_c = mb_convert_encoding($row['maincontact_c'], 'UTF-8');
            }
        }
    }

}
