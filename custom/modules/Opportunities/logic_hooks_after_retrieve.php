<?php

class LXOpportunitiesAfterRetrieveMethods
{
    public function setMainContactC(&$bean, $event, $arguments)
    {
        if (isset($_REQUEST['module'], $_REQUEST['action']) && $_REQUEST['module'] == 'Opportunities' && $_REQUEST['action'] == 'DetailView') {
            // $bean->custom_fields->retrieve(); // it seems that this method is no longer requiered. LionixCRM
            $query = "
                select trim(concat(ifnull(first_name,''), ' ',ifnull(last_name,''), ' ',ifnull(lastname2_c,''))) AS 'maincontact_c'
                from contacts
                LEFT JOIN contacts_cstm ON id = id_c
                where id = '{$bean->maincontact_c}'
            ";
            $results = $bean->db->query($query);
            if ($row = $bean->db->fetchByAssoc($results)) {
                global $sugar_config;
                $url = $sugar_config['site_url'];
                $contact_full_name = mb_convert_encoding($row['maincontact_c'], 'UTF-8');
                $bean->maincontact_c = "<a href=\"{$url}/index.php?module=Contacts&action=DetailView&record={$bean->maincontact_c}\">{$contact_full_name}</a>";
            }
        }
    }

    public function setLinksToUploadedFiles($bean, $event, $arguments)
    {
        if (isset($_REQUEST['module'], $_REQUEST['action']) && $_REQUEST['module'] == 'Opportunities' && $_REQUEST['action'] == 'DetailView') {
            global $sugar_config;
            $url = $sugar_config['site_url'];
            $files_fields = $sugar_config['lionixcrm']['modules']['opportunities']['upload_files_fields'];
            foreach ($files_fields as $ff) {
                $json_to_show = json_decode(html_entity_decode($bean->{$ff['field_name']}));
                $url = "{$json_to_show->note_link}";
                $bean->{$ff['field_name']} = "<a href=\"$url\">{$json_to_show->note_name}</a>";
            }
        }
    }
}
