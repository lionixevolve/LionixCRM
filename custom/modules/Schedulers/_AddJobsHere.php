<?php
//Miscellaneous Jobs.
$job_strings[] = 'infoticos';
$job_strings[] = 'campaignLogDeletEr';
//Jobs that sends mail.
$job_strings[] = 'updateProspectListProspects';
$job_strings[] = 'emailManEr';
//Jobs required for LARGE reports tables.

function infoticos()
{
    // records without soundex_c or birthdate defined.
    global $db;
    $query = "
        (SELECT id_c AS 'contact_id'
         FROM contacts_cstm cc
         INNER JOIN contacts c on cc.id_c = c.id
         WHERE deleted = 0
         and cc.soundex_c IS NULL LIMIT 50)
    UNION
        (SELECT id_c AS 'contact_id'
         FROM contacts_cstm cc
         INNER JOIN contacts c ON cc.id_c = c.id
         INNER JOIN infoticos.nacimientos nac ON cc.cedula_c = nac.cedula
         WHERE cc.soundex_c NOT IN ('sin',
                                    'mal',
                                    'ner')
             AND c.birthdate IS NULL LIMIT 50)
    ";
    $rs = $db->query($query);
    while (($row = $db->fetchByAssoc($rs)) != null) {
        // sp_infoticos updates soundex_c and birthdate on contacts.
        $query = "call sp_infoticos('{$row['contact_id']}')";
        $db->query($query);
    }
    return true;
}//fin infoticos

function campaignLogDeletEr()
{
    // Must be run once, one hour before emailManEr, eg: 8:00am
    global $db;
    $query = '
        DELETE
        FROM campaign_log
        WHERE campaign_id IN
                ( SELECT id
                 FROM campaigns ca
                 INNER JOIN campaigns_cstm cac ON ca.id = cac.id_c
                 WHERE deleted = 0
                     AND clearcamplogdaily_c = 1)
    ';
    $db->query($query);

    return true;
}//campaignLogDeletEr

function updateProspectListProspects()
{
    // Must be run once, before emailManEr, usually in the dawn, eg: 2:00am
    global $db,$sugar_config;
    // Prospect lists with autofill_c and autoclean_c are cleaned, to be filled again.
    $query = "
        select id as 'prospect_list_id'
        from prospect_lists p
        inner join prospect_lists_cstm pc on p.id = pc.id_c
        where pc.autofill_c = 1
        and pc.autoclean_c = 1
    ";
    $rs = $db->query($query);
    while (($row = $db->fetchByAssoc($rs)) != null) {
        $query = "delete from prospect_lists_prospects where prospect_list_id = '{$row['prospect_list_id']}'";
        $db->query($query);
    }

    // Prospect lists with autofill_c are filled according to database "vista" linked.
    $query = "
        select table_name as 'view_name'
        from information_schema.views
        where table_schema = '{$sugar_config['dbconfig']['db_name']}'
        and table_name like 'vista_cron_pl_%'
    ";
    $rs = $db->query($query);
    while (($row = $db->fetchByAssoc($rs)) != null) {
        $query = "insert into prospect_lists_prospects (id,prospect_list_id,related_id,related_type,date_modified,deleted)
                   select id,prospect_list_id,related_id,related_type,date_modified,deleted
                   from {$row['view_name']}
                   ";
        $db->query($query);
    }//fin while

    return true;
}//fin updateProspectListProspects

function emailManEr()
{
    // Must be run once, eg: 9:00am
    global $db;
    $query = "
        INSERT INTO emailman(date_entered,user_id,in_queue,send_attempts,deleted,campaign_id,marketing_id,list_id,send_date_time,related_id,related_type) -- Para cuando está marcado en email_marketing que use todas las listas de prospectos.

        SELECT -- campos que van para la tabla emailman
        utc_timestamp() AS 'date_entered',
        1 AS 'user_id',
        0 AS 'in_queue',
        0 AS 'send_attempts',
        0 AS 'deleted',
        ca.id AS 'campaign_id',
        em.id AS 'marketing_id',
        pl.id AS 'list_id',
        '1980-02-01 06:00' AS 'send_date_time',
        plp.related_id,
        plp.related_type
        FROM prospect_lists_prospects plp
        LEFT JOIN prospect_lists pl ON plp.prospect_list_id = pl.id
        LEFT JOIN prospect_list_campaigns plc ON pl.id = plc.prospect_list_id
        LEFT JOIN campaigns ca ON plc.campaign_id = ca.id
        LEFT JOIN campaigns_cstm cac ON ca.id = cac.id_c
        LEFT JOIN email_marketing em ON ca.id = em.campaign_id
        WHERE plp.deleted = 0
            AND pl.deleted = 0
            AND plc.deleted = 0 -- con 1
        AND ca.deleted = 0
            AND em.deleted = 0
            AND em.all_prospect_lists = 1
            AND pl.list_type = 'default'
            AND ca.status = 'active'
            AND cac.emailmaner_c = 1
        UNION -- Para cuando está marcado en email_marketing que use solamente las listas de prospectos seleccionadas.

        SELECT -- campos que van para la tabla emailman
        DISTINCT utc_timestamp() AS 'date_entered',
                 1 AS 'user_id',
                 0 AS 'in_queue',
                 0 AS 'send_attempts',
                 0 AS 'deleted',
                 ca.id AS 'campaign_id',
                 em.id AS 'marketing_id',
                 pl.id AS 'list_id',
                 '1980-02-01 06:00' AS 'send_date_time',
                 plp.related_id,
                 plp.related_type
        FROM prospect_lists_prospects plp
        LEFT JOIN prospect_lists pl ON plp.prospect_list_id = pl.id
        LEFT JOIN email_marketing_prospect_lists empl ON pl.id = empl.prospect_list_id -- con 0
        LEFT JOIN email_marketing em ON empl.email_marketing_id = em.id
        LEFT JOIN campaigns ca ON em.campaign_id = ca.id
        LEFT JOIN campaigns_cstm cac ON ca.id = cac.id_c
        WHERE plp.deleted = 0
            AND pl.deleted = 0
            AND empl.deleted = 0 -- con 0
        AND em.deleted = 0
            AND ca.deleted = 0
            AND em.all_prospect_lists = 0
            AND pl.list_type = 'default'
            AND ca.status = 'active'
            AND cac.emailmaner_c = 1
    ";
    $db->query($query);

    return true;
}//emailManEr
