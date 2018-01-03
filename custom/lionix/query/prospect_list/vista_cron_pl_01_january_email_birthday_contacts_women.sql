CREATE OR REPLACE VIEW vista_cron_pl_01_january_email_birthday_contacts_women AS
SELECT
	DISTINCT uuid() AS id,
	pl.id AS prospect_list_id,
	c.id AS related_id,
	'Contacts' AS related_type,
	UTC_TIMESTAMP() AS date_modified,
	0 AS deleted,
	'01_january_email_birthday_contacts_women' AS pl_name,
	plc.autofill_c AS pl_autofill,
	plc.autoclean_c AS pl_autoclean
FROM
	contacts c
LEFT JOIN contacts_cstm cc ON
	c.id = cc.id_c
LEFT JOIN prospect_lists pl ON
	pl.name = '01_january_email_birthday_contacts_women'
INNER JOIN prospect_lists_cstm plc ON
	pl.id = plc.id_c
LEFT JOIN infoticos.nacimientos nac ON
	cc.cedula_c = nac.cedula
WHERE
	c.deleted = 0
	AND nac.sexo = 2
	AND DATE_FORMAT( c.birthdate, '%m' )= DATE_FORMAT( date_add( '1980-01-15', INTERVAL - 6 HOUR ), '%m' )
	AND DATE_FORMAT( nac.fecha_suceso, '%m' )= DATE_FORMAT( date_add( '1980-01-15', INTERVAL - 6 HOUR ), '%m' )
	AND plc.autofill_c = '1'
	AND c.id NOT IN(
		SELECT
			related_id
		FROM
			prospect_lists_prospects plp
		WHERE
			plp.deleted = 0
			AND plp.prospect_list_id IN(
				SELECT
					id
				FROM
					prospect_lists pl
				LEFT JOIN prospect_lists_cstm plc ON
					pl.id = plc.id_c
				WHERE
					name = '01_january_email_birthday_contacts_women'
					AND plc.autofill_c = 1
			)
	)
#última línea
