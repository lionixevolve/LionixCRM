-- DROP PROCEDURE IF EXISTS sp_infoticos;
-- DELIMITER //
-- Delimiter isn't need on php
CREATE PROCEDURE sp_install_routines
()
BEGIN
###### custom fields on cases module
ALTER TABLE cases_cstm add COLUMN elapsedtimeinmins_c int(255) DEFAULT '0' NULL;

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4) VALUES ('Caseselapsedtimeinmins_c','elapsedtimeinmins_c','LBL_ELAPSEDTIMEINMINS','This time is calculated on schedulers with function WORKDAY_TIME_DIFF_HOLIDAY_TABLE','This time is calculated on schedulers with function WORKDAY_TIME_DIFF_HOLIDAY_TABLE','Cases','int','255','0','0',utc_timestamp(),'0','0','0','0','1','false','','','1','');

###### custom fields on campaigns module
CREATE TABLE campaigns_cstm (id_c char(36) NOT NULL, PRIMARY KEY (id_c)) CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE campaigns_cstm add COLUMN emailmaner_c bool DEFAULT '0' NULL;
ALTER TABLE campaigns_cstm add COLUMN clearcamplogdaily_c bool DEFAULT '0' NULL;

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('Campaignsemailmaner_c','emailmaner_c','LBL_EMAILMANER','','','Campaigns','bool',255,0,'0',utc_timestamp(),0,0,0,0,1,'false','','','','');

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('Campaignsclearcamplogdaily_c','clearcamplogdaily_c','LBL_CLEARCAMPLOGDAILY','','','Campaigns','bool',255,0,'0',utc_timestamp(),0,0,0,0,1,'false','','','','');

###### custom fields and records on prospect_lists module
CREATE TABLE prospect_lists_cstm (id_c char(36) NOT NULL, PRIMARY KEY (id_c)) CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE prospect_lists_cstm add COLUMN autofill_c bool DEFAULT '0' NULL;
ALTER TABLE prospect_lists_cstm add COLUMN autoclean_c bool DEFAULT '0' NULL;

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('ProspectListsautofill_c','autofill_c','LBL_AUTOFILL','autofill must be false until there\'s certainty that would be use','','ProspectLists','bool',255,0,'0',utc_timestamp(),0,0,0,0,1,'true','','','','');

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('ProspectListsautoclean_c','autoclean_c','LBL_AUTOCLEAN','autoclean must be false until there\'s certainty that would be use','','ProspectLists','bool',255,0,'0',utc_timestamp(),0,0,0,0,1,'true','','','','');

INSERT INTO prospect_lists_cstm (id_c ,autoclean_c ,autofill_c ) VALUES ('daily-email-bday-congrats-contacts' ,'1' ,'1' );

INSERT INTO prospect_lists (assigned_user_id,id,name,list_type,date_entered,date_modified,modified_user_id,created_by,deleted,description,domain_name)
VALUES ('1','daily-email-bday-congrats-contacts','daily_email_birthday_congratulations_contacts','default',utc_timestamp(),utc_timestamp(),'1','1',0,'Autoclean and autofill must be set to true always, this list is used by emailManEr function on schedulers.','');

###### custom fields on contacts module
ALTER TABLE contacts_cstm add COLUMN soundex_c varchar(3) NULL;
ALTER TABLE contacts_cstm add COLUMN cedula_c varchar(255) NULL;

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('Contactssoundex_c','soundex_c','LBL_SOUNDEX','Allowed values are AAA,AA,A,B,C,D,NER,MAL,SIN','','Contacts','varchar',3,0,'',utc_timestamp(),0,0,0,0,1,'true','','','','');

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('Contactscedula_c','cedula_c','LBL_CEDULA','','','Contacts','varchar',255,0,'','2016-07-14 20:08:17',0,0,0,0,1,'true','','','','');

###### custom fields on accounts module
ALTER TABLE accounts_cstm add COLUMN tipocedula_c varchar(100) NULL;
ALTER TABLE accounts_cstm add COLUMN cedula_c varchar(255) NULL;

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('Accountstipocedula_c','tipocedula_c','LBL_TIPOCEDULA','','','Accounts','enum',100,0,'',utc_timestamp(),0,0,0,0,1,'true','account_tipocedula_list','','','');

INSERT INTO fields_meta_data (id,name,vname,comments,help,custom_module,type,len,required,default_value,date_modified,deleted,audited,massupdate,duplicate_merge,reportable,importable,ext1,ext2,ext3,ext4)
VALUES ('Accountscedula_c','cedula_c','LBL_CEDULA','','','Accounts','varchar',255,0,'','2016-07-14 20:08:17',0,0,0,0,1,'true','','','','');

###### custom schedulers
INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up)
VALUES ('infoticos', '0', utc_timestamp(), utc_timestamp(), '1', '1', '99- LionixCRM - INFOTICOS - Check against TSE CR', 'function::infoticos', '1980-02-01 06:00:00', '*/2::*::*::*::*', utc_timestamp(), 'Active', '0');

INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up)
VALUES ('updateelapsedtimeinmins', '0', utc_timestamp(), utc_timestamp(), '1', '1', '99- LionixCRM - updateElapsedTimeInMins', 'function::updateElapsedTimeInMins', '1980-02-01 06:00:00', '*::*::*::*::*', utc_timestamp(), 'Active', '0');

INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up) VALUES ('updateprospectlistprospects', '0', utc_timestamp(), utc_timestamp(), '1', '1', '01- LionixCRM - Prospect List Prospects Update 2:00am', 'function::updateProspectListProspects', '1980-02-01 06:00:00', '00::02::*::*::*', utc_timestamp(), 'Active', '0');

INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up) VALUES ('campaignlogdeleter', '0', utc_timestamp(), utc_timestamp(), '1', '1', '02- LionixCRM - CampaignLogDeletEr - 8:00am', 'function::campaignLogDeletEr', '1980-02-01 06:00:00', '00::08::*::*::*', utc_timestamp(), 'Active', '0');

INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, job_interval, last_run, status, catch_up) VALUES ('emailmaner', '0', utc_timestamp(), utc_timestamp(), '1', '1', '03- LionixCRM - EmailManEr - 9:00am', 'function::emailManEr', '1980-02-01 06:00:00', '00::09::*::*::*', utc_timestamp(), 'Active', '0');

###### holiday records
INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-01-01', '2016-01-01', DATE_FORMAT('2016-01-01','%W'), 'Año Nuevo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-04-11', '2016-04-11', DATE_FORMAT('2016-04-11','%W'), 'Día de Juan Santamaría', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-05-01', '2016-05-01', DATE_FORMAT('2016-05-01','%W'), 'Día Internacional del Trabajo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-07-25', '2016-07-25', DATE_FORMAT('2016-07-25','%W'), 'Anexión del Partido de Nicoya a Costa ica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-08-02', '2016-08-02', DATE_FORMAT('2016-08-02','%W'), 'Día de la Virgen de los Ángeles', 'CR'); -- Pago no obligatorio;

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-08-15', '2016-08-15', DATE_FORMAT('2016-08-15','%W'), 'Día de la Madre', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-09-15', '2016-09-15', DATE_FORMAT('2016-09-15','%W'), 'Independencia de Costa Rica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-10-12', '2016-10-12', DATE_FORMAT('2016-10-12','%W'), 'Día de las Culturas', 'CR'); -- Pago noobligatorio;

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-12-25', '2016-12-25', DATE_FORMAT('2016-12-25','%W'), 'Navidad', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-01-01', '2017-01-01', DATE_FORMAT('2017-01-01','%W'), 'Año Nuevo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-04-11', '2017-04-11', DATE_FORMAT('2017-04-11','%W'), 'Día de Juan Santamaría', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-05-01', '2017-05-01', DATE_FORMAT('2017-05-01','%W'), 'Día Internacional del Trabajo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-07-25', '2017-07-25', DATE_FORMAT('2017-07-25','%W'), 'Anexión del Partido de Nicoya a Costa ica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-08-02', '2017-08-02', DATE_FORMAT('2017-08-02','%W'), 'Día de la Virgen de los Ángeles', 'CR'); -- Pago no obligatorio;

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-08-15', '2017-08-15', DATE_FORMAT('2017-08-15','%W'), 'Día de la Madre', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-09-15', '2017-09-15', DATE_FORMAT('2017-09-15','%W'), 'Independencia de Costa Rica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-10-12', '2017-10-12', DATE_FORMAT('2017-10-12','%W'), 'Día de las Culturas', 'CR'); -- Pago noobligatorio;

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-12-25', '2017-12-25', DATE_FORMAT('2017-12-25','%W'), 'Navidad', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-01-01', '2018-01-01', DATE_FORMAT('2018-01-01','%W'), 'Año Nuevo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-04-11', '2018-04-11', DATE_FORMAT('2018-04-11','%W'), 'Día de Juan Santamaría', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-05-01', '2018-05-01', DATE_FORMAT('2018-05-01','%W'), 'Día Internacional del Trabajo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-07-25', '2018-07-25', DATE_FORMAT('2018-07-25','%W'), 'Anexión del Partido de Nicoya a Costa ica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-08-02', '2018-08-02', DATE_FORMAT('2018-08-02','%W'), 'Día de la Virgen de los Ángeles', 'CR'); -- Pago no obligatorio;

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-08-15', '2018-08-15', DATE_FORMAT('2018-08-15','%W'), 'Día de la Madre', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-09-15', '2018-09-15', DATE_FORMAT('2018-09-15','%W'), 'Independencia de Costa Rica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-10-12', '2018-10-12', DATE_FORMAT('2018-10-12','%W'), 'Día de las Culturas', 'CR'); -- Pago noobligatorio;

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-12-25', '2018-12-25', DATE_FORMAT('2018-12-25','%W'), 'Navidad', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-01-01', '2019-01-01', DATE_FORMAT('2019-01-01','%W'), 'Año Nuevo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-04-11', '2019-04-11', DATE_FORMAT('2019-04-11','%W'), 'Día de Juan Santamaría', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-05-01', '2019-05-01', DATE_FORMAT('2019-05-01','%W'), 'Día Internacional del Trabajo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-07-25', '2019-07-25', DATE_FORMAT('2019-07-25','%W'), 'Anexión del Partido de Nicoya a Costa ica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-08-02', '2019-08-02', DATE_FORMAT('2019-08-02','%W'), 'Día de la Virgen de los Ángeles', 'CR'); -- Pago no obligatorio;

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-08-15', '2019-08-15', DATE_FORMAT('2019-08-15','%W'), 'Día de la Madre', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-09-15', '2019-09-15', DATE_FORMAT('2019-09-15','%W'), 'Independencia de Costa Rica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-10-12', '2019-10-12', DATE_FORMAT('2019-10-12','%W'), 'Día de las Culturas', 'CR'); -- Pago noobligatorio;

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-12-25', '2019-12-25', DATE_FORMAT('2019-12-25','%W'), 'Navidad', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-01-01', '2020-01-01', DATE_FORMAT('2020-01-01','%W'), 'Año Nuevo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-04-11', '2020-04-11', DATE_FORMAT('2020-04-11','%W'), 'Día de Juan Santamaría', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-05-01', '2020-05-01', DATE_FORMAT('2020-05-01','%W'), 'Día Internacional del Trabajo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-07-25', '2020-07-25', DATE_FORMAT('2020-07-25','%W'), 'Anexión del Partido de Nicoya a Costa ica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-08-02', '2020-08-02', DATE_FORMAT('2020-08-02','%W'), 'Día de la Virgen de los Ángeles', 'CR'); -- Pago no obligatorio;

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-08-15', '2020-08-15', DATE_FORMAT('2020-08-15','%W'), 'Día de la Madre', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-09-15', '2020-09-15', DATE_FORMAT('2020-09-15','%W'), 'Independencia de Costa Rica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-10-12', '2020-10-12', DATE_FORMAT('2020-10-12','%W'), 'Día de las Culturas', 'CR'); -- Pago noobligatorio;

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-12-25', '2020-12-25', DATE_FORMAT('2020-12-25','%W'), 'Navidad', 'CR');

##### workflows
INSERT INTO aow_workflow
(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, flow_module, flow_run_on, status, run_when, multiple_runs)
VALUES('wf-op-reset-date-closed', 'WF-OP-RESET-DATE_CLOSED_NOTIFIED_C', utc_timestamp(), utc_timestamp(), '1', '1', 'Mark dateclosednotified_c as not notified', 0, '1', 'Opportunities', 'All_Records', 'Active', 'On_Save', 1);

INSERT INTO aow_conditions
(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, aow_workflow_id, condition_order, module_path, field, operator, value_type, value)
VALUES('wf-op-reset-date-closed-cond-1', '', utc_timestamp(), utc_timestamp(), '1', '1', NULL, 0, 'wf-op-reset-date-closed', 1, 'YToxOntpOjA7czoxMzoiT3Bwb3J0dW5pdGllcyI7fQ==', 'date_closed', 'Equal_To', 'Any_Change', NULL);

INSERT INTO aow_actions
(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, aow_workflow_id, action_order, `action`, parameters)
VALUES('wf-op-reset-date-closed-action-1', 'Enable future notifications for date_closed', utc_timestamp(), utc_timestamp(), '1', '1', NULL, 0, 'wf-op-reset-date-closed', 1, 'ModifyRecord', 'YTo1OntzOjExOiJyZWNvcmRfdHlwZSI7czoxMzoiT3Bwb3J0dW5pdGllcyI7czo4OiJyZWxfdHlwZSI7czoxMzoiT3Bwb3J0dW5pdGllcyI7czo1OiJmaWVsZCI7YToxOntpOjA7czoyMDoiZGF0ZWNsb3NlZG5vdGlmaWVkX2MiO31zOjEwOiJ2YWx1ZV90eXBlIjthOjE6e2k6MDtzOjU6IlZhbHVlIjt9czo1OiJ2YWx1ZSI7YToxOntpOjA7czoxOiIwIjt9fQ==');

INSERT INTO aow_workflow
(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, flow_module, flow_run_on, status, run_when, multiple_runs)
VALUES('wf-op-not-date-closed', 'WF-OP-NOT-DATE_CLOSED: 3 days before', utc_timestamp(), utc_timestamp(), '1', '1', 'Send an email to assigned_user_id and then mark dateclosednotified_c as notified.', 0, '1', 'Opportunities', 'All_Records', 'Inactive', 'In_Scheduler', 1);

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
VALUES('wf-op-not-date-closed-action-2', 'Mark record as notified', '2017-06-16 23:09:31.000', '2017-06-16 23:19:15.000', '1', '1', NULL, 0, 'wf-op-not-date-closed', 2, 'ModifyRecord', 'YTo1OntzOjExOiJyZWNvcmRfdHlwZSI7czoxMzoiT3Bwb3J0dW5pdGllcyI7czo4OiJyZWxfdHlwZSI7czoxMzoiT3Bwb3J0dW5pdGllcyI7czo1OiJmaWVsZCI7YToxOntpOjA7czoyMDoiZGF0ZWNsb3NlZG5vdGlmaWVkX2MiO31zOjEwOiJ2YWx1ZV90eXBlIjthOjE6e2k6MDtzOjU6IlZhbHVlIjt9czo1OiJ2YWx1ZSI7YToxOntpOjA7czoxOiIxIjt9fQ==');

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
VALUES('01abcdef-efef-efef-efef-efefefefefef', utc_timestamp(), utc_timestamp(), '1', '1', 'off', 'z_WF_NOT_OP_SALES_STAGE_CHANGE', NULL, NULL, ' La oportunidad$opportunity_name requiere tu atención Detalle:Nombre: $opportunity_nameHaz click en el nombre de la oportunidad para verla en LionixCRM', '<div class="mozaik-inner" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px 30px;margin:0px auto;width:1014px;background-color:rgb(250,250,250);"><table class="mce-item-table" style="width:100%;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:3px 3px 3px 0px;margin:0px;"><tbody style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;"><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;width:10%;"><img style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;" src="custom/themes/default/images/company_logo.png" alt="Alternativa - Furniture - Solutions" width="200" height="40" /></td><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;width:89%;"><h1 style="font-family:Arial, Helvetica, sans-serif;font-size:24px;line-height:38.4px;color:#444444;padding:0px;margin:0px;">La oportunidad $opportunity_name requiere tu atención</h1></td></tr></tbody></table><div class="mozaik-clear" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px;height:0px;"> <br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px auto;" /></div></div><div class="mozaik-inner" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px 30px;margin:0px auto;width:1014px;background-color:rgb(250,250,250);"><table style="width:100%;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:3px 3px 3px 0px;margin:0px;" class="mce-item-table"><tbody style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;"><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;width:49.0278%;"><h2 style="font-family:Arial, Helvetica, sans-serif;font-size:18px;line-height:28.8px;color:#444444;padding:0px;margin:0px;">Detalle</h2></td><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;width:49.9722%;"><h2 style="font-family:Arial, Helvetica, sans-serif;font-size:18px;line-height:28.8px;color:#444444;padding:0px;margin:0px;">Descripción</h2></td></tr><tr style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:5px 0px;margin:0px;"><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;width:49.0278%;"><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;">Nombre: <a style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;" href="index.php?module=Opportunities&action=DetailView&record=$opportunity_id">$opportunity_name</a></p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;">Cuenta: <a style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;" href="index.php?module=Accounts&action=DetailView&record=$opportunity_account_id">$opportunity_account_name</a></p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;">Etapa de Ventas: $opportunity_sales_stage</p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;">Fecha de Creación: $opportunity_date_entered</p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;">Fecha de Cierre: $opportunity_date_closed</p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;">Asignado a: $opportunity_assigned_user_name</p><p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;"> </p></td><td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#534d64;padding:3px 3px 3px 0px;margin:0px;width:49.9722%;">$opportunity_description</td></tr></tbody></table><div class="mozaik-clear" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;height:0px;"> </div></div><div class="mozaik-inner" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px 30px;margin:0px auto;width:1014px;background-color:rgb(250,250,250);"><p class="footer" style="font-family:Arial, Helvetica, sans-serif;font-size:10px;line-height:16px;color:#444444;padding:20px;margin:0px;">Haz click en el nombre de la oportunidad o cuenta para verla en LionixCRM<br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;" /></p><div class="mozaik-clear" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px;height:0px;"> <br style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:rgb(68,68,68);padding:0px;margin:0px auto;" /></div></div>', 0, '1', 0, NULL);

###### el fin
END
-- // DELIMITER ;
