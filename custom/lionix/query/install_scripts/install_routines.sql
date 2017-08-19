-- DROP PROCEDURE IF EXISTS sp_infoticos;
-- DELIMITER //
-- Delimiter isn't need on php
CREATE PROCEDURE sp_install_routines
()
BEGIN
###### dolars when default system currency is colón
# tipo cambio = 580 | conversion_rate = 1/580
INSERT INTO currencies
(id, name, symbol, iso4217, conversion_rate, status, deleted, date_entered, date_modified, created_by)
VALUES('1', 'Dólar', '$', 'USD', 0.00172413793103448276, 'Active', 0, utc_timestamp(), utc_timestamp(), '1');

###### outbound_email configuration
UPDATE config SET value = 'LionixCRM' WHERE category = 'notify' AND name = 'fromname';
INSERT INTO outbound_email
(id, name, `type`, user_id, mail_sendtype, mail_smtptype, mail_smtpserver, mail_smtpport, mail_smtpuser, mail_smtppass, mail_smtpauth_req, mail_smtpssl, date_entered, date_modified, modified_user_id, created_by, deleted, assigned_user_id)
VALUES('test-lionix-org', 'system', 'system', '1', 'SMTP', 'other', 'smtp.gmail.com', 465, 'test@lionix.org', 'kmmxI9mASUU=', 1, '1', NULL, NULL, NULL, NULL, 0, NULL);
delete from outbound_email where id != 'test-lionix-org';

###### default users
INSERT INTO users
(id, user_name, user_hash, system_generated_password, pwd_last_changed, authenticate_id, sugar_login, first_name, last_name, is_admin, external_auth_only, receive_notifications, description, date_entered, date_modified, modified_user_id, created_by, title, photo, department, phone_home, phone_mobile, phone_work, phone_other, phone_fax, status, address_street, address_city, address_state, address_country, address_postalcode, deleted, portal_only, show_on_employees, employee_status, messenger_id, messenger_type, reports_to_id, is_group)
VALUES('2', 'sync', md5(utc_timestamp()), 1, utc_timestamp(), NULL, 1, 'Sync Bot', 'Lionix evolve', 0, 0, 1, '', utc_timestamp(), utc_timestamp(), '1', '1', '', NULL, '', '', '', '', '', '', 'Active', '', '', '', 'Costa Rica', '', 0, 0, 1, 'Active', '', '', '', 0);

INSERT INTO users
(id, user_name, user_hash, system_generated_password, pwd_last_changed, authenticate_id, sugar_login, first_name, last_name, is_admin, external_auth_only, receive_notifications, description, date_entered, date_modified, modified_user_id, created_by, title, photo, department, phone_home, phone_mobile, phone_work, phone_other, phone_fax, status, address_street, address_city, address_state, address_country, address_postalcode, deleted, portal_only, show_on_employees, employee_status, messenger_id, messenger_type, reports_to_id, is_group)
VALUES('reasignar', 'reasignar', md5(utc_timestamp()), 1, utc_timestamp(), NULL, 1, 'Reasignar', 'Usuario temporal', 0, 0, 1, '', utc_timestamp(), utc_timestamp(), '1', '1', '', NULL, '', '', '', '', '', '', 'Active', '', '', '', '', '', 0, 0, 0, 'Active', '', '', '', 0);

INSERT INTO users
(id, user_name, user_hash, system_generated_password, pwd_last_changed, authenticate_id, sugar_login, first_name, last_name, is_admin, external_auth_only, receive_notifications, description, date_entered, date_modified, modified_user_id, created_by, title, photo, department, phone_home, phone_mobile, phone_work, phone_other, phone_fax, status, address_street, address_city, address_state, address_country, address_postalcode, deleted, portal_only, show_on_employees, employee_status, messenger_id, messenger_type, reports_to_id, is_group)
VALUES('bot', 'bot', md5(utc_timestamp()), 1, utc_timestamp(), NULL, 1, 'Multi Purpose Bot', 'Lionix evolve', 0, 0, 1, '', utc_timestamp(), utc_timestamp(), '1', '1', '', NULL, '', '', '', '', '', '', 'Active', '', '', '', '', '', 0, 0, 0, 'Active', '', '', '', 0);

INSERT INTO users
(id, user_name, user_hash, system_generated_password, pwd_last_changed, authenticate_id, sugar_login, first_name, last_name, reports_to_id, is_admin, external_auth_only, receive_notifications, description, date_entered, date_modified, modified_user_id, created_by, title, department, phone_home, phone_mobile, phone_work, phone_other, phone_fax, status, address_street, address_city, address_state, address_country, address_postalcode, deleted, portal_only, employee_status, messenger_id, messenger_type, is_group, show_on_employees)
VALUES('test', 'test', 'md5(utc_timestamp())', 0, NULL, NULL, 1, 'Usuario Pruebas', 'Lionix evolve', NULL, 0, 0, 1, NULL, utc_timestamp(), utc_timestamp(), '1', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, 0, 0, 'Active', NULL, NULL, 0, 1);

INSERT INTO users
(id, user_name, user_hash, system_generated_password, pwd_last_changed, authenticate_id, sugar_login, first_name, last_name, is_admin, external_auth_only, receive_notifications, description, date_entered, date_modified, modified_user_id, created_by, title, photo, department, phone_home, phone_mobile, phone_work, phone_other, phone_fax, status, address_street, address_city, address_state, address_country, address_postalcode, deleted, portal_only, show_on_employees, employee_status, messenger_id, messenger_type, reports_to_id, is_group)
VALUES('support', 'support', md5(utc_timestamp()), 1, utc_timestamp(), NULL, 1, 'Soporte', 'Lionix evolve', 0, 0, 1, '', utc_timestamp(), utc_timestamp(), '1', '1', '', NULL, '', '', '', '', '', '', 'Active', '', '', '', '', '', 0, 0, 0, 'Active', '', '', '', 0);

###### custom fields on cases module
ALTER TABLE cases_cstm add COLUMN elapsedtimeinmins_c int(255) DEFAULT '0' NULL;
ALTER TABLE cases_cstm add COLUMN lxtid_c varchar(36) NULL;
ALTER TABLE cases_cstm add COLUMN lxtname_c varchar(255) NULL;
ALTER TABLE cases_cstm add COLUMN lxtescalation_c bool NULL;
ALTER TABLE cases_cstm add COLUMN lxtlevel2_c varchar(255) NULL;
ALTER TABLE cases_cstm add COLUMN lxtlevel3_c varchar(255) NULL;
ALTER TABLE cases_cstm add COLUMN lxtlevel4_c varchar(255) NULL;
ALTER TABLE cases_cstm add COLUMN lxtlevel5_c varchar(255) NULL;
ALTER TABLE cases_cstm add COLUMN lxtlevel6_c varchar(255) NULL;
ALTER TABLE cases_cstm add COLUMN lxtlevel7_c varchar(255) NULL;
ALTER TABLE cases_cstm add COLUMN lxtprocedure1_c text NULL;
ALTER TABLE cases_cstm add COLUMN lxtprocedure2_c text NULL;
ALTER TABLE cases_cstm add COLUMN lxtprocedure3_c text NULL;

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4) VALUES ('Caseselapsedtimeinmins_c', 'elapsedtimeinmins_c', 'LBL_ELAPSEDTIMEINMINS', 'This time is calculated on schedulers with function WORKDAY_TIME_DIFF_HOLIDAY_TABLE', 'This time is calculated on schedulers with function WORKDAY_TIME_DIFF_HOLIDAY_TABLE', 'Cases', 'int', '255', '0', '0',utc_timestamp(),'0', '0', '0', '0', '1', 'false', '', '', '1', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Caseslxtid_c', 'lxtid_c', 'LBL_LXTID', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Cases', 'varchar', 36, 0, '', utc_timestamp(), 0, 1, 0, 0, 1, 'false', '', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Caseslxtname_c', 'lxtname_c', 'LBL_LXTNAME', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Cases', 'varchar', 255, 0, NULL, utc_timestamp(), 0, 1, 0, 0, 1, 'fals3', NULL, NULL, NULL, NULL);

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('Caseslxtescalation_c', 'lxtescalation_c', 'LBL_LXTESCALATION', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Cases', 'bool', 255, 0,'0',utc_timestamp(), 0, 0, 0, 0,1,'false', '', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Caseslxtlevel2_c', 'lxtlevel2_c', 'LBL_LXTLEVEL2', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Cases', 'varchar', 255, 0, NULL, utc_timestamp(), 0, 1, 0, 0, 1, 'false', NULL, NULL, NULL, NULL);

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Caseslxtlevel3_c', 'lxtlevel3_c', 'LBL_LXTLEVEL3', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Cases', 'varchar', 255, 0, NULL, utc_timestamp(), 0, 1, 0, 0, 1, 'false', NULL, NULL, NULL, NULL);

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Caseslxtlevel4_c', 'lxtlevel4_c', 'LBL_LXTLEVEL4', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Cases', 'varchar', 255, 0, NULL, utc_timestamp(), 0, 1, 0, 0, 1, 'false', NULL, NULL, NULL, NULL);

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Caseslxtlevel5_c', 'lxtlevel5_c', 'LBL_LXTLEVEL5', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Cases', 'varchar', 255, 0, NULL, utc_timestamp(), 0, 1, 0, 0, 1, 'false', NULL, NULL, NULL, NULL);

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Caseslxtlevel6_c', 'lxtlevel6_c', 'LBL_LXTLEVEL6', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Cases', 'varchar', 255, 0, NULL, utc_timestamp(), 0, 1, 0, 0, 1, 'false', NULL, NULL, NULL, NULL);

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Caseslxtlevel7_c', 'lxtlevel7_c', 'LBL_LXTLEVEL7', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Cases', 'varchar', 255, 0, NULL, utc_timestamp(), 0, 1, 0, 0, 1, 'false', NULL, NULL, NULL, NULL);

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Caseslxtprocedure1_c', 'lxtprocedure1_c', 'LBL_LXTPROCEDURE1', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Cases', 'varchar', 255, 0, '', utc_timestamp(), 0, 1, 0, 0, 1, 'false', '', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Caseslxtprocedure2_c', 'lxtprocedure2_c', 'LBL_LXTPROCEDURE2', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Cases', 'varchar', 255, 0, '', utc_timestamp(), 0, 1, 0, 0, 1, 'false', '', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Caseslxtprocedure3_c', 'lxtprocedure3_c', 'LBL_LXTPROCEDURE3', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Cases', 'varchar', 255, 0, '', utc_timestamp(), 0, 1, 0, 0, 1, 'false', '', '', '', '');

###### custom fields on calls module
CREATE TABLE calls_cstm (id_c char(36) NOT NULL, PRIMARY KEY (id_c)) CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE calls_cstm add COLUMN lxtid_c varchar(36) NULL;
ALTER TABLE calls_cstm add COLUMN lxtname_c varchar(255) NULL;
ALTER TABLE calls_cstm add COLUMN lxtescalation_c bool NULL;
ALTER TABLE calls_cstm add COLUMN lxtlevel2_c varchar(255) NULL;
ALTER TABLE calls_cstm add COLUMN lxtlevel3_c varchar(255) NULL;
ALTER TABLE calls_cstm add COLUMN lxtlevel4_c varchar(255) NULL;
ALTER TABLE calls_cstm add COLUMN lxtlevel5_c varchar(255) NULL;
ALTER TABLE calls_cstm add COLUMN lxtlevel6_c varchar(255) NULL;
ALTER TABLE calls_cstm add COLUMN lxtlevel7_c varchar(255) NULL;
ALTER TABLE calls_cstm add COLUMN lxtprocedure1_c text NULL;
ALTER TABLE calls_cstm add COLUMN lxtprocedure2_c text NULL;
ALTER TABLE calls_cstm add COLUMN lxtprocedure3_c text NULL;

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Callslxtid_c', 'lxtid_c', 'LBL_LXTID', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Calls', 'varchar', 36, 0, '', utc_timestamp(), 0, 1, 0, 0, 1, 'false', '', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Callslxtname_c', 'lxtname_c', 'LBL_LXTNAME', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Calls', 'varchar', 255, 0, NULL, utc_timestamp(), 0, 1, 0, 0, 1, 'fals3', NULL, NULL, NULL, NULL);

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('Callslxtescalation_c', 'lxtescalation_c', 'LBL_LXTESCALATION', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Calls', 'bool', 255, 0,'0',utc_timestamp(), 0, 0, 0, 0,1,'false', '', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Callslxtlevel2_c', 'lxtlevel2_c', 'LBL_LXTLEVEL2', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Calls', 'varchar', 255, 0, NULL, utc_timestamp(), 0, 1, 0, 0, 1, 'false', NULL, NULL, NULL, NULL);

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Callslxtlevel3_c', 'lxtlevel3_c', 'LBL_LXTLEVEL3', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Calls', 'varchar', 255, 0, NULL, utc_timestamp(), 0, 1, 0, 0, 1, 'false', NULL, NULL, NULL, NULL);

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Callslxtlevel4_c', 'lxtlevel4_c', 'LBL_LXTLEVEL4', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Calls', 'varchar', 255, 0, NULL, utc_timestamp(), 0, 1, 0, 0, 1, 'false', NULL, NULL, NULL, NULL);

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Callslxtlevel5_c', 'lxtlevel5_c', 'LBL_LXTLEVEL5', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Calls', 'varchar', 255, 0, NULL, utc_timestamp(), 0, 1, 0, 0, 1, 'false', NULL, NULL, NULL, NULL);

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Callslxtlevel6_c', 'lxtlevel6_c', 'LBL_LXTLEVEL6', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Calls', 'varchar', 255, 0, NULL, utc_timestamp(), 0, 1, 0, 0, 1, 'false', NULL, NULL, NULL, NULL);

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Callslxtlevel7_c', 'lxtlevel7_c', 'LBL_LXTLEVEL7', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Calls', 'varchar', 255, 0, NULL, utc_timestamp(), 0, 1, 0, 0, 1, 'false', NULL, NULL, NULL, NULL);

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Callslxtprocedure1_c', 'lxtprocedure1_c', 'LBL_LXTPROCEDURE1', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Calls', 'varchar', 255, 0, '', utc_timestamp(), 0, 1, 0, 0, 1, 'false', '', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Callslxtprocedure2_c', 'lxtprocedure2_c', 'LBL_LXTPROCEDURE2', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Calls', 'varchar', 255, 0, '', utc_timestamp(), 0, 1, 0, 0, 1, 'false', '', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Callslxtprocedure3_c', 'lxtprocedure3_c', 'LBL_LXTPROCEDURE3', 'LionixCRM - lx_topics', 'LionixCRM - lx_topics', 'Calls', 'varchar', 255, 0, '', utc_timestamp(), 0, 1, 0, 0, 1, 'false', '', '', '', '');

###### custom fields on campaigns module
CREATE TABLE campaigns_cstm (id_c char(36) NOT NULL, PRIMARY KEY (id_c)) CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE campaigns_cstm add COLUMN emailmaner_c bool DEFAULT '0' NULL;
ALTER TABLE campaigns_cstm add COLUMN clearcamplogdaily_c bool DEFAULT '0' NULL;

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('Campaignsemailmaner_c', 'emailmaner_c', 'LBL_EMAILMANER', 'LionixCRM', 'LionixCRM', 'Campaigns', 'bool', 255, 0, '0', utc_timestamp(), 0, 0, 0, 0,1,'false', '', '', '', '');

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('Campaignsclearcamplogdaily_c', 'clearcamplogdaily_c', 'LBL_CLEARCAMPLOGDAILY', 'LionixCRM', 'LionixCRM', 'Campaigns', 'bool', 255, 0, '0', utc_timestamp(), 0, 0, 0, 0,1,'false', '', '', '', '');

###### custom fields and records on prospect_lists module
CREATE TABLE prospect_lists_cstm (id_c char(36) NOT NULL, PRIMARY KEY (id_c)) CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE prospect_lists_cstm add COLUMN autofill_c bool DEFAULT '0' NULL;
ALTER TABLE prospect_lists_cstm add COLUMN autoclean_c bool DEFAULT '0' NULL;

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('ProspectListsautofill_c', 'autofill_c', 'LBL_AUTOFILL', 'autofill must be false until there\'s certainty that would be use', 'LionixCRM', 'ProspectLists', 'bool', 255, 0,'0',utc_timestamp(), 0, 0, 0, 0,1,'true', '', '', '', '');

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('ProspectListsautoclean_c', 'autoclean_c', 'LBL_AUTOCLEAN', 'autoclean must be false until there\'s certainty that would be use', 'LionixCRM', 'ProspectLists', 'bool', 255, 0,'0',utc_timestamp(), 0, 0, 0, 0,1,'true', '', '', '', '');

INSERT INTO prospect_lists_cstm (id_c ,autoclean_c ,autofill_c ) VALUES ('daily-email-bday-congrats-contacts' ,'1' ,'1' );

INSERT INTO prospect_lists (assigned_user_id,id,name,list_type,date_entered,date_modified,modified_user_id,created_by,deleted,description,domain_name)
VALUES ('1', 'daily-email-bday-congrats-contacts', 'daily_email_birthday_congratulations_contacts', 'default',utc_timestamp(),utc_timestamp(),'1', '1', 0,'Autoclean and autofill must be set to true always, this list is used by emailManEr function on schedulers.', '');

###### custom fields on opportunities module
ALTER TABLE opportunities_cstm add COLUMN dateclosednotified_c bool DEFAULT '0' NULL;
ALTER TABLE opportunities_cstm add COLUMN maincontact_c varchar(100) NULL;
ALTER TABLE opportunities_cstm add COLUMN maincontactfirstname_c varchar(100) NULL;
ALTER TABLE opportunities_cstm add COLUMN maincontactlastname_c varchar(100) NULL;
ALTER TABLE opportunities_cstm add COLUMN maincontactlastname2_c varchar(100) NULL;
ALTER TABLE opportunities_cstm add COLUMN maincontactphonework_c varchar(100) NULL;
ALTER TABLE opportunities_cstm add COLUMN maincontactemailaddress_c varchar(255) NULL;
ALTER TABLE opportunities_cstm add COLUMN maincontacttitle_c varchar(100) NULL;
ALTER TABLE opportunities_cstm add COLUMN maincontactcedula_c varchar(255) NULL;

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Opportunitiesdateclosednotified_c', 'dateclosednotified_c', 'LBL_DATECLOSEDNOTIFIED', 'LionixCRM', 'LionixCRM', 'Opportunities', 'bool', 255, 0, '0', utc_timestamp(), 0, 1, 0, 0, 1, 'false', '', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Opportunitiesmaincontact_c', 'maincontact_c', 'LBL_MAINCONTACT', 'LionixCRM', 'LionixCRM', 'Opportunities', 'enum', 100, 0, NULL, utc_timestamp, 0, 1, 0, 0, 1, 'false', 'lx_empty_list', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Opportunitiesmaincontactfirstname_c', 'maincontactfirstname_c', 'LBL_MAINCONTACTFIRSTNAME', 'LionixCRM', 'LionixCRM', 'Opportunities', 'varchar', 100, 0, '', utc_timestamp(), 0, 0, 0, 0, 1, 'false', '', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Opportunitiesmaincontactlastname_c', 'maincontactlastname_c', 'LBL_MAINCONTACTLASTNAME', 'LionixCRM', 'LionixCRM', 'Opportunities', 'varchar', 100, 0, '', utc_timestamp(), 0, 0, 0, 0, 1, 'false', '', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Opportunitiesmaincontactlastname2_c', 'maincontactlastname2_c', 'LBL_MAINCONTACTLASTNAME2', 'LionixCRM', 'LionixCRM', 'Opportunities', 'varchar', 100, 0, '', utc_timestamp(), 0, 0, 0, 0, 1, 'false', '', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Opportunitiesmaincontactphonework_c', 'maincontactphonework_c', 'LBL_MAINCONTACTPHONEWORK', 'LionixCRM', 'LionixCRM', 'Opportunities', 'varchar', 100, 0, '', utc_timestamp(), 0, 0, 0, 0, 1, 'false', '', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Opportunitiesmaincontactemailaddress_c', 'maincontactemailaddress_c', 'LBL_MAINCONTACTEMAILADDRESS', 'LionixCRM', 'LionixCRM', 'Opportunities', 'varchar', 255, 0, '', utc_timestamp(), 0, 0, 0, 0, 1, 'false', '', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Opportunitiesmaincontacttitle_c', 'maincontacttitle_c', 'LBL_MAINCONTACTTITLE', 'LionixCRM', 'LionixCRM', 'Opportunities', 'varchar', 100, 0, '', utc_timestamp(), 0, 0, 0, 0, 1, 'false', '', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Opportunitiesmaincontactcedula_c', 'maincontactcedula_c', 'LBL_MAINCONTACTCEDULA', 'LionixCRM', 'LionixCRM', 'Opportunities', 'varchar', 255, 0, '', utc_timestamp(), 0, 0, 0, 0, 1, 'false', '', '', '', '');

###### custom fields on contacts module
ALTER TABLE contacts_cstm add COLUMN soundex_c varchar(3) NULL;
ALTER TABLE contacts_cstm add COLUMN cedula_c varchar(255) NULL;
ALTER TABLE contacts_cstm add COLUMN lastname2_c varchar(100) NULL;

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('Contactssoundex_c', 'soundex_c', 'LBL_SOUNDEX', 'Allowed values are AAA,AA,A,B,C,D,NER,MAL,SIN', 'LionixCRM', 'Contacts', 'varchar', 3, 0, '', utc_timestamp(), 0, 0, 0, 0, 1, 'true', '', '', '', '');

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('Contactscedula_c' ,'cedula_c' ,'LBL_CEDULA' ,'LionixCRM', 'LionixCRM', 'Contacts', 'varchar', 255, 0 ,'' ,utc_timestamp(), 0, 0, 0, 0, 1, 'true', '', '', '', '');

INSERT INTO fields_meta_data
(id, name, vname, comments, help, custom_module, `type`, len, required, default_value, date_modified, deleted, audited, massupdate, duplicate_merge, reportable, importable, ext1, ext2, ext3, ext4)
VALUES('Contactslastname2_c', 'lastname2_c', 'LBL_LASTNAME2', '', '', 'Contacts', 'varchar', 100, 0, '', utc_timestamp(), 0, 0, 0, 0, 1, 'true', '', '', '', '');

###### custom fields on accounts module
ALTER TABLE accounts_cstm add COLUMN tipocedula_c varchar(100) NULL;
ALTER TABLE accounts_cstm add COLUMN cedula_c varchar(255) NULL;

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('Accountstipocedula_c', 'tipocedula_c', 'LBL_TIPOCEDULA', 'LionixCRM', 'LionixCRM', 'Accounts', 'enum',100, 0, '', utc_timestamp(), 0, 0, 0, 0,1,'true', 'account_tipocedula_list', '', '', '');

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('Accountscedula_c', 'cedula_c', 'LBL_CEDULA', 'LionixCRM', 'LionixCRM', 'Accounts', 'varchar', 255, 0, '', utc_timestamp(), 0, 0, 0, 0,1,'true', '', '', '', '');

###### custom schedulers
INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up)
VALUES ('infoticos', '0', utc_timestamp(), utc_timestamp(), '1', '1', '99- LionixCRM - INFOTICOS - Check against TSE CR', 'function::infoticos', '1980-02-01 06:00:00', '*/2::*::*::*::*', utc_timestamp(), 'Active', '0');

INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up)
VALUES ('updateelapsedtimeinmins', '0', utc_timestamp(), utc_timestamp(), '1', '1', '99- LionixCRM - updateElapsedTimeInMins', 'function::updateElapsedTimeInMins', '1980-02-01 06:00:00', '*::*::*::*::*', utc_timestamp(), 'Active', '0');

INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up)
VALUES ('updateholidays', '0', utc_timestamp(), utc_timestamp(), '1', '1', '99- LionixCRM - updateHolidays', 'function::updateHolidays', '1980-02-01 06:00:00', '*::*::*::*::*', utc_timestamp(), 'Active', '0');

INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up) VALUES ('updateprospectlistprospects', '0', utc_timestamp(), utc_timestamp(), '1', '1', '01- LionixCRM - Prospect List Prospects Update 2:00am', 'function::updateProspectListProspects', '1980-02-01 06:00:00', '00::02::*::*::*', utc_timestamp(), 'Active', '0');

INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up) VALUES ('campaignlogdeleter', '0', utc_timestamp(), utc_timestamp(), '1', '1', '02- LionixCRM - CampaignLogDeletEr - 8:00am', 'function::campaignLogDeletEr', '1980-02-01 06:00:00', '00::08::*::*::*', utc_timestamp(), 'Active', '0');

INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up) VALUES ('emailmaner', '0', utc_timestamp(), utc_timestamp(), '1', '1', '03- LionixCRM - EmailManEr - 9:00am', 'function::emailManEr', '1980-02-01 06:00:00', '00::09::*::*::*', utc_timestamp(), 'Active', '0');

###### holiday records
SET @year = year(UTC_TIMESTAMP);
INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES (CONCAT(@year,'-01-01'), CONCAT(@year,'-01-01'), DATE_FORMAT(CONCAT(@year,'-01-01'),'%W'), 'Año Nuevo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES (CONCAT(@year,'-04-11'), CONCAT(@year,'-04-11'), DATE_FORMAT(CONCAT(@year,'-04-11'),'%W'), 'Día de Juan Santamaría', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES (CONCAT(@year,'-05-01'), CONCAT(@year,'-05-01'), DATE_FORMAT(CONCAT(@year,'-05-01'),'%W'), 'Día Internacional del Trabajo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES (CONCAT(@year,'-07-25'), CONCAT(@year,'-07-25'), DATE_FORMAT(CONCAT(@year,'-07-25'),'%W'), 'Anexión del Partido de Nicoya a Costa ica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES (CONCAT(@year,'-08-02'), CONCAT(@year,'-08-02'), DATE_FORMAT(CONCAT(@year,'-08-02'),'%W'), 'Día de la Virgen de los Ángeles', 'CR'); -- Pago no obligatorio;

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES (CONCAT(@year,'-08-15'), CONCAT(@year,'-08-15'), DATE_FORMAT(CONCAT(@year,'-08-15'),'%W'), 'Día de la Madre', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES (CONCAT(@year,'-09-15'), CONCAT(@year,'-09-15'), DATE_FORMAT(CONCAT(@year,'-09-15'),'%W'), 'Independencia de Costa Rica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES (CONCAT(@year,'-10-12'), CONCAT(@year,'-10-12'), DATE_FORMAT(CONCAT(@year,'-10-12'),'%W'), 'Día de las Culturas', 'CR'); -- Pago no obligatorio;

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES (CONCAT(@year,'-12-25'), CONCAT(@year,'-12-25'), DATE_FORMAT(CONCAT(@year,'-12-25'),'%W'), 'Navidad', 'CR');

##### workflows
INSERT INTO aow_workflow
(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, flow_module, flow_run_on, status, run_when, multiple_runs)
VALUES('wf-op-reset-date-closed', 'WF-OP-NOT-DATE_CLOSED: Reset date_closed_notified_c', utc_timestamp(), utc_timestamp(), '1', '1', 'Mark dateclosednotified_c as not notified', 0, '1', 'Opportunities', 'All_Records', 'Active', 'On_Save', 1);

INSERT INTO aow_conditions
(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, aow_workflow_id, condition_order, module_path, field, operator, value_type, value)
VALUES('wf-op-reset-date-closed-cond-1', '', utc_timestamp(), utc_timestamp(), '1', '1', NULL, 0, 'wf-op-reset-date-closed', 1, 'YToxOntpOjA7czoxMzoiT3Bwb3J0dW5pdGllcyI7fQ==', 'date_closed', 'Equal_To', 'Any_Change', NULL);

INSERT INTO aow_actions
(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, aow_workflow_id, action_order, `action`, parameters)
VALUES('wf-op-reset-date-closed-action-1', 'Enable future notifications for date_closed', utc_timestamp(), utc_timestamp(), '1', '1', NULL, 0, 'wf-op-reset-date-closed', 1, 'ModifyRecord', 'YTo1OntzOjExOiJyZWNvcmRfdHlwZSI7czoxMzoiT3Bwb3J0dW5pdGllcyI7czo4OiJyZWxfdHlwZSI7czoxMzoiT3Bwb3J0dW5pdGllcyI7czo1OiJmaWVsZCI7YToxOntpOjA7czoyMDoiZGF0ZWNsb3NlZG5vdGlmaWVkX2MiO31zOjEwOiJ2YWx1ZV90eXBlIjthOjE6e2k6MDtzOjU6IlZhbHVlIjt9czo1OiJ2YWx1ZSI7YToxOntpOjA7czoxOiIwIjt9fQ==');

INSERT INTO aow_workflow
(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, flow_module, flow_run_on, status, run_when, multiple_runs)
VALUES('wf-op-not-date-closed', 'WF-OP-NOT-DATE_CLOSED: Notify 3 days before', utc_timestamp(), utc_timestamp(), '1', '1', 'Send an email to assigned_user_id and then mark dateclosednotified_c as notified.', 0, '1', 'Opportunities', 'All_Records', 'Active', 'In_Scheduler', 1);

INSERT INTO aow_conditions
(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, aow_workflow_id, condition_order, module_path, field, operator, value_type, value)
VALUES('wf-op-not-date-closed-cond-1', '', utc_timestamp(), utc_timestamp(), '1', '1', NULL, 0, 'wf-op-not-date-closed', 1, 'YToxOntpOjA7czoxMzoiT3Bwb3J0dW5pdGllcyI7fQ==', 'sales_stage', 'Not_Equal_To', 'Multi', '^Closed Won^,^Closed Lost^');
INSERT INTO aow_conditions
(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, aow_workflow_id, condition_order, module_path, field, operator, value_type, value)
VALUES('wf-op-not-date-closed-cond-2', '', utc_timestamp(), utc_timestamp(), '1', '1', NULL, 0, 'wf-op-not-date-closed', 2, 'YToxOntpOjA7czoxMzoiT3Bwb3J0dW5pdGllcyI7fQ==', 'date_closed', 'Greater_Than', 'Date', 'YTo0OntpOjA7czo1OiJ0b2RheSI7aToxO3M6NToibWludXMiO2k6MjtzOjE6IjMiO2k6MztzOjM6ImRheSI7fQ==');
INSERT INTO aow_conditions
(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, aow_workflow_id, condition_order, module_path, field, operator, value_type, value)
VALUES('wf-op-not-date-closed-cond-3', '', utc_timestamp(), utc_timestamp(), '1', '1', NULL, 0, 'wf-op-not-date-closed', 3, 'YToxOntpOjA7czoxMzoiT3Bwb3J0dW5pdGllcyI7fQ==', 'dateclosednotified_c', 'Equal_To', 'Value', '0');

INSERT INTO aow_actions
(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, aow_workflow_id, action_order, `action`, parameters)
VALUES('wf-op-not-date-closed-action-1', 'Send email to assigned_user_id', utc_timestamp(), utc_timestamp(), '1', '1', NULL, 0, 'wf-op-not-date-closed', 1, 'SendEmail', 'YTo1OntzOjE2OiJpbmRpdmlkdWFsX2VtYWlsIjtzOjE6IjAiO3M6MTQ6ImVtYWlsX3RlbXBsYXRlIjtzOjM2OiIwMWFiY2RlZi1lZmVmLWVmZWYtZWZlZi1lZmVmZWZlZmVmZWYiO3M6MTM6ImVtYWlsX3RvX3R5cGUiO2E6MTp7aTowO3M6MjoidG8iO31zOjE3OiJlbWFpbF90YXJnZXRfdHlwZSI7YToxOntpOjA7czoxMzoiUmVsYXRlZCBGaWVsZCI7fXM6NToiZW1haWwiO2E6MTp7aTowO3M6MTg6ImFzc2lnbmVkX3VzZXJfbmFtZSI7fX0=');
INSERT INTO aow_actions
(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, aow_workflow_id, action_order, `action`, parameters)
VALUES('wf-op-not-date-closed-action-2', 'Mark record as notified', utc_timestamp(), utc_timestamp(), '1', '1', NULL, 0, 'wf-op-not-date-closed', 2, 'ModifyRecord', 'YTo1OntzOjExOiJyZWNvcmRfdHlwZSI7czoxMzoiT3Bwb3J0dW5pdGllcyI7czo4OiJyZWxfdHlwZSI7czoxMzoiT3Bwb3J0dW5pdGllcyI7czo1OiJmaWVsZCI7YToxOntpOjA7czoyMDoiZGF0ZWNsb3NlZG5vdGlmaWVkX2MiO31zOjEwOiJ2YWx1ZV90eXBlIjthOjE6e2k6MDtzOjU6IlZhbHVlIjt9czo1OiJ2YWx1ZSI7YToxOntpOjA7czoxOiIxIjt9fQ==');

###### birthday email campaign (status=inactive)
INSERT INTO campaigns (id, name, date_entered, date_modified, modified_user_id, created_by, deleted, assigned_user_id, tracker_count, refer_url, start_date, end_date, status, impressions, currency_id, campaign_type, content) VALUES ('daily-email-bday-congrats-campaign', 'daily_email_birthday_congratulations_campaign', utc_timestamp(), utc_timestamp(), '1', '1', '0', '1', '0', 'http://', '1980-02-01', '1980-02-01', 'Inactive', '0', '-99', 'Email', 'Listas de Público Objetivo = daily_email_birthday_congratulations_contacts\r\nPlantillas de Email = z_daily_email_birthday_congratulations_template\r\n\r\nEsta campaña se usa de template para alimentar la tabla emailman mediante varios scheduler.\r\n\r\nSon 3: \r\n01- LionixCRM - Prospect List Prospects Update 2:00am\r\n02- LionixCRM - CampaignLogDeletEr - 8:00am\r\n03- LionixCRM - EmailManEr - 9:00am\r\n\r\n-- última línea');

INSERT INTO campaigns_cstm (id_c, emailmaner_c, clearcamplogdaily_c) VALUES ('daily-email-bday-congrats-campaign', '1', '1');

INSERT INTO email_marketing (id, deleted, date_entered, date_modified, modified_user_id, created_by, name, from_name, inbound_email_id, date_start, template_id, status, campaign_id, outbound_email_id, all_prospect_lists)
VALUES ('daily-email-bday-congrats-campaign', '0', utc_timestamp(), utc_timestamp(), '1', '1', 'daily_email_bday_congrats', 'Fanatics Club', null, utc_timestamp(), 'bda8bda8-bda8-bda8-bda8-bda8bda8bda8', 'active', 'daily-email-bday-congrats-campaign', '0', '0');

INSERT INTO prospect_list_campaigns (id, prospect_list_id, campaign_id, date_modified, deleted) VALUES ('daily-email-bday-congrats-campaign', 'daily-email-bday-congrats-contacts', 'daily-email-bday-congrats-campaign', utc_timestamp(), '0');

##### email templates
#/*z_daily_email_bday_congrats_template*/
INSERT INTO email_templates
(id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, `type`)
VALUES('bda8bda8-bda8-bda8-bda8-bda8bda8bda8', utc_timestamp(), utc_timestamp(), '1', '1', 'off', 'z_daily_email_birthday_congratulations_template', 'This template is used to send automatically email congratulations to contacts on daily_email_birthday_congratulations_contacts target list.', 'Te deseamos un ¡Feliz cumpleaños!', '¡Muy feliz cumpleaños $contact_first_name!', '<div class="mozaik-inner" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px 30px;margin:0px;"><p style="text-align:left;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><span style="font-family:tahoma, arial, helvetica, sans-serif;font-size:24px;line-height:38.4px;color:#444444;padding:0px;margin:0px;">¡Muy feliz cumpleaños $contact_first_name!</span></p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><img style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;" src="custom/lionix/img/happy-birthday-to-you.jpg" alt="happy-birthday-to-you.jpg" width="842" height="500" /></p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"></p><div class="mozaik-clear" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;height:0px;"></div></div>', 0, '1', 0, 'campaign');

#/*z_WF_NOT_OP_SALES_STAGE_CHANGE*/
INSERT INTO email_templates
(id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, `type`)
VALUES('01abcdef-efef-efef-efef-efefefefefef', utc_timestamp(), utc_timestamp(), '1', '1', 'off', 'z_WF_NOT_OP_SALES_STAGE_CHANGE', 'This template is used to send notifications to opportunty assigned user.', 'LionixCRM - Oportunidad $opportunity_name requiere tu atención', 'Oportunidad requiere tu atención

Detalle
Nombre: $opportunity_name
Cuenta: $opportunity_account_name
Etapa de Ventas: $opportunity_sales_stage
Fecha de Creación: $opportunity_date_entered
Fecha de Cierre: $opportunity_date_closed
Asignado a: $opportunity_assigned_user_name

Descripción: $opportunity_description

metadata:
- opportunity_url: $url', '<div class="mozaik-inner" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px 30px;margin:0px auto;width:1017px;background-color:rgb(250,250,250);"><h1 style="font-family:Arial, Helvetica, sans-serif;font-size:24px;line-height:38.4px;color:#444444;padding:0px;margin:0px;"><img src="https://user-images.githubusercontent.com/6874136/29391774-c943b25a-82b6-11e7-9567-ffc7bafa6582.png" alt="LionixCRM" width="300" height="32" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;" /><br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px auto;" />Oportunidad require tu atenci&oacute;n</h1><div class="mozaik-clear" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px;height:0px;">&nbsp;<br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px auto;" /></div></div><div class="mozaik-inner" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px 30px;margin:0px auto;width:1017px;background-color:rgb(250,250,250);"><h2 style="font-family:Arial, Helvetica, sans-serif;font-size:18px;line-height:28.8px;color:#444444;padding:0px;margin:0px;">Detalle<br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px auto;" /></h2><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;">Nombre: <a href="$url" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px auto;">$opportunity_name</a><br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px auto;" /></p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;">Cuenta: $opportunity_account_name<br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px auto;" />Etapa de Ventas: $opportunity_sales_stage<br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px auto;" />Fecha de Creaci&oacute;n: $opportunity_date_entered<br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px auto;" />Fecha de Cierre: $opportunity_date_closed<br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px auto;" />Asignado a: $opportunity_assigned_user_name</p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px auto;" /></p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;">Descripci&oacute;n: $opportunity_description</p><div class="mozaik-clear" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px;height:0px;">&nbsp;<br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px auto;" /></div></div><div class="mozaik-inner" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px 30px;margin:0px auto;width:1014px;background-color:rgb(250,250,250);"><p class="footer" style="font-family:Arial, Helvetica, sans-serif;font-size:10px;line-height:16px;color:#444444;padding:20px;margin:0px;">Haz click en el nombre de la oportunidad para verla en LionixCRM<br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px auto;" />metadata:<br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px auto;" />- opportunity_url: $url</p><div class="mozaik-clear" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px;height:0px;">&nbsp;<br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px auto;" /></div></div>', 0, '1', 0, NULL);

###### el fin
END
-- // DELIMITER ;
