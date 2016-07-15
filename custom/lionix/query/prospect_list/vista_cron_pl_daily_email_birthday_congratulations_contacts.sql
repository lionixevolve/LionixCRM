CREATE OR REPLACE VIEW vista_cron_pl_daily_email_birthday_congratulations_contacts AS
SELECT distinct uuid() AS id
    ,pl.id AS prospect_list_id
    ,c.id AS related_id
    ,'Contacts' AS related_type
    ,utc_timestamp() AS date_modified
    ,0 AS deleted
    ,'daily_email_birthday_congratulations_contacts' AS pl_name
    ,plc.autofill_c AS pl_autofill
    ,plc.autoclean_c AS pl_autoclean
FROM contacts c
    left join contacts_cstm cc on c.id = cc.id_c
    left join prospect_lists pl on pl.name = 'daily_email_birthday_congratulations_contacts'
    INNER JOIN prospect_lists_cstm plc
        ON pl.id=plc.id_c
WHERE c.deleted=0
    and date_format(c.birthdate,'%m-%d') = date_format(date_add(utc_timestamp(), interval -6 hour),'%m-%d')
    AND plc.autofill_c='1'
    AND c.id NOT IN (
        SELECT related_id
        FROM prospect_lists_prospects plp
        WHERE plp.deleted = 0
        and plp.prospect_list_id in (select id from prospect_lists pl left join prospect_lists_cstm plc on pl.id = plc.id_c where name = 'daily_email_birthday_congratulations_contacts' and plc.autofill_c = 1 )
    )
#última línea
