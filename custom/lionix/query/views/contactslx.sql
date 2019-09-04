CREATE VIEW contactslx AS
SELECT c.id AS contact_id,
       trim(concat(ifnull(c.first_name,''), ' ', ifnull(c.last_name,''))) AS full_name,
       group_concat(DISTINCT lower(e.email_address) SEPARATOR ', ') AS emails,
       trim(concat(ifnull(c.phone_mobile,''),' ',ifnull(c.phone_home,''),' ',ifnull(c.phone_work,''),' ',ifnull(c.phone_other,''),' ',ifnull(c.phone_fax,''),' ',ifnull(c.assistant_phone,''))) AS phone_numbers,
       c.*,
       cc.*
FROM contacts c
LEFT JOIN contacts_cstm cc ON c.id = cc.id_c
LEFT JOIN email_addr_bean_rel er ON c.id = er.bean_id
AND er.deleted = 0
AND er.bean_module = 'contacts'
LEFT JOIN email_addresses e ON er.email_address_id = e.id
AND e.deleted = 0
WHERE c.deleted = 0 --     AND c.id = '1000000400-con-asp'
GROUP BY c.id -- LIMIT 1
