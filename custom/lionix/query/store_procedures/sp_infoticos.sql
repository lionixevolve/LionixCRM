-- DROP PROCEDURE IF EXISTS sp_infoticos;
-- DELIMITER //
CREATE PROCEDURE sp_infoticos
(IN p_contact_id varchar(36))
BEGIN
    declare vcount smallint;
    declare vfound tinyint(1);

    set vfound = 0;

###### Actualización de soundex_c con espacios en blanco
    update contacts_cstm cc set cc.soundex_c = null where soundex_c = '';

###### Actualización de cédulas con formato #-####-#### a CR Standard
    update contacts_cstm cc set cc.cedula_c = replace(replace(trim(cc.cedula_c),' ',''),'-','')
    where cc.soundex_c is null
    and trim(cc.cedula_c) not rlike '^[1-9]{1}[0-9]{8}$'
    and (
        trim(cc.cedula_c) rlike '^[1-9]{1}[ ,-]{1}[0-9]{4}[ ,-]{1}[0-9]{4}$'
    or  trim(cc.cedula_c) rlike '^[1-9]{1}[ ,-]{1}[0-9]{8}$'
    )
    and cc.id_c = p_contact_id
    ;

###### Actualización de cédulas con formato #-###-### a CR Standard
    update contacts_cstm cc set cc.cedula_c = replace(replace(trim(cc.cedula_c),' ','0'),'-','0')
    where cc.soundex_c is null
    and trim(cc.cedula_c) not rlike '^[1-9]{1}[0-9]{8}$'
    and trim(cc.cedula_c) rlike '^[1-9]{1}[ ,-]{1}[0-9]{3}[ ,-]{1}[0-9]{3}$'
    and cc.id_c = p_contact_id
    ;

####### Actualización de Soundex_c AAA
    select count(1) from
    contacts c
    inner join contacts_cstm cc on c.id = cc.id_c
    inner join infoticos.nacimientos n on trim(cc.cedula_c) = n.cedula
    #set cc.soundex_c = 'AAA'
    where cc.soundex_c is null
    and trim(cc.cedula_c) rlike '^[1-9]{1}[0-9]{8}$'
    and concat(trim(c.first_name),' ',trim(c.last_name))
        = concat(trim(n.nombre),' ',trim(n.`apellido_1`),' ',trim(`apellido_2`)) COLLATE utf8_unicode_ci
    and cc.id_c = p_contact_id
    into vcount
    ;

    if vcount = 1 then
        update
        contacts_cstm cc
        set cc.soundex_c = 'AAA'
        where cc.id_c = p_contact_id
        ;
        set vfound = 1;
    end if;

####### Actualización de Soundex_c AA
    if vfound = 0 then
        select count(1) from
        contacts c
        inner join contacts_cstm cc on c.id = cc.id_c
        inner join infoticos.nacimientos n on trim(cc.cedula_c) = n.cedula
        #set cc.soundex_c = 'AA'
        where cc.soundex_c is null
        and trim(cc.cedula_c) rlike '^[1-9]{1}[0-9]{8}$'
        and soundex(concat(ifnull(c.first_name,''),' ',ifnull(c.last_name,'')))
            = soundex(concat(n.nombre,' ',n.`apellido_1`,' ',`apellido_2`)) COLLATE utf8_unicode_ci
        and cc.id_c = p_contact_id
        into vcount
        ;

        if vcount = 1 then
            update
            contacts_cstm cc
            set cc.soundex_c = 'AA'
            where cc.id_c = p_contact_id
            ;
            set vfound = 1;
        end if;
    end if;

####### Actualización de Soundex_c A
    if vfound = 0 then
        select count(1) from
        contacts c
        inner join contacts_cstm cc on c.id = cc.id_c
        inner join infoticos.nacimientos n on trim(cc.cedula_c) = n.cedula
        #set cc.soundex_c = 'A'
        where cc.soundex_c is null
        and trim(cc.cedula_c) rlike '^[1-9]{1}[0-9]{8}$'
        and substring(soundex(concat(ifnull(c.first_name,''),' ',ifnull(c.last_name,''))),1,4)
            = substring(soundex(concat(n.nombre,' ',n.`apellido_1`,' ',`apellido_2`)),1,4) COLLATE utf8_unicode_ci
        and cc.id_c = p_contact_id
        into vcount
        ;

        if vcount = 1 then
            update
            contacts_cstm cc
            set cc.soundex_c = 'A'
            where cc.id_c = p_contact_id
            ;
            set vfound = 1;
        end if;
    end if;

####### Actualización de Soundex_c B
    if vfound = 0 then
        select count(1) from
        contacts c
        inner join contacts_cstm cc on c.id = cc.id_c
        inner join infoticos.nacimientos n on trim(cc.cedula_c) = n.cedula
        #set cc.soundex_c = 'B'
        where cc.soundex_c is null
        and trim(cc.cedula_c) rlike '^[1-9]{1}[0-9]{8}$'
        and substring(soundex(concat(ifnull(c.first_name,''),' ',ifnull(c.last_name,''))),1,3)
            = substring(soundex(concat(n.nombre,' ',n.`apellido_1`,' ',`apellido_2`)),1,3) COLLATE utf8_unicode_ci
        and cc.id_c = p_contact_id
        into vcount
        ;

        if vcount = 1 then
            update
            contacts_cstm cc
            set cc.soundex_c = 'B'
            where cc.id_c = p_contact_id
            ;
            set vfound = 1;
        end if;
    end if;

####### Actualización de Soundex_c C
    if vfound = 0 then
        select count(1) from
        contacts c
        inner join contacts_cstm cc on c.id = cc.id_c
        inner join infoticos.nacimientos n on trim(cc.cedula_c) = n.cedula
        #set cc.soundex_c = 'C'
        where cc.soundex_c is null
        and trim(cc.cedula_c) rlike '^[1-9]{1}[0-9]{8}$'
        and substring(soundex(concat(ifnull(c.first_name,''),' ',ifnull(c.last_name,''))),1,1)
            = substring(soundex(concat(n.nombre,' ',n.`apellido_1`,' ',`apellido_2`)),1,1) COLLATE utf8_unicode_ci
        and cc.id_c = p_contact_id
        into vcount
        ;

        if vcount = 1 then
            update
            contacts_cstm cc
            set cc.soundex_c = 'C'
            where cc.id_c = p_contact_id
            ;
            set vfound = 1;
        end if;
    end if;


####### Actualización de Soundex_c D
    if vfound = 0 then
        select count(1) from
        contacts c
        inner join contacts_cstm cc on c.id = cc.id_c
        inner join infoticos.nacimientos n on trim(cc.cedula_c) = n.cedula
        #set cc.soundex_c = 'D'
        where cc.soundex_c is null
        and trim(cc.cedula_c) rlike '^[1-9]{1}[0-9]{8}$'
        and substring(soundex(concat(ifnull(c.first_name,''),' ',ifnull(c.last_name,''))),1,4)
            != substring(soundex(concat(n.nombre,' ',n.`apellido_1`,' ',`apellido_2`)),1,4) COLLATE utf8_unicode_ci
        and cc.id_c = p_contact_id
        into vcount
        ;

        if vcount = 1 then
            update
            contacts_cstm cc
            set cc.soundex_c = 'D'
            where cc.id_c = p_contact_id
            ;
            set vfound = 1;
        end if;
    end if;

####### Actualización de Soundex_c NER luego de haber aplicado todas las reglas anteriores (NER = NO EXISTE REGISTRO)
    if vfound = 0 then
            select count(1) from
            contacts_cstm cc
            #set cc.soundex_c = 'NER'
            where soundex_c is null
            and trim(cc.cedula_c) rlike '^[1-9]{1}[0-9]{8}$'
            and cc.id_c = p_contact_id
            into vcount
            ;
        if vcount = 1 then
            update
            contacts_cstm cc
            set cc.soundex_c = 'NER'
            where cc.id_c = p_contact_id
            ;
            set vfound = 1;
        end if;
    end if;

####### Actualización de Soundex_c MAL luego de haber aplicado todas las reglas anteriores
    if vfound = 0 then
        select count(1) from
        contacts_cstm cc
        #set cc.soundex_c = 'MAL'
        where soundex_c is null
        and trim(cc.cedula_c) not rlike '^[1-9]{1}[0-9]{8}$'
        and cc.id_c = p_contact_id
        into vcount
        ;
        if vcount = 1 then
            update
            contacts_cstm cc
            set cc.soundex_c = 'MAL'
            where cc.id_c = p_contact_id
            ;
            set vfound = 1;
        end if;
    end if;

####### Actualización de Soundex_c SIN luego de haber aplicado todas las reglas anteriores
    if vfound = 0 then
        update
        contacts_cstm cc
        set cc.soundex_c = 'SIN'
        where cc.soundex_c is null
        and cc.cedula_c is null
        and cc.id_c = p_contact_id
        ;
    end if;

####### Actualización de fecha de nacimiento
    update contacts c
    inner join contacts_cstm cc on c.id = cc.id_c
    inner join infoticos.nacimientos nac on cc.cedula_c = nac.cedula
    set c.birthdate = cast(nac.fecha_suceso as date)
    where c.birthdate is null
    and c.id = p_contact_id
    ;

END
-- // DELIMITER ;
