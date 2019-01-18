INSERT INTO `users` (`id`, `user_name`, `user_hash`, `system_generated_password`, `pwd_last_changed`, `authenticate_id`, `sugar_login`, `first_name`, `last_name`, `is_admin`, `external_auth_only`, `receive_notifications`, `description`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `title`, `photo`, `department`, `phone_home`, `phone_mobile`, `phone_work`, `phone_other`, `phone_fax`, `status`, `address_street`, `address_city`, `address_state`, `address_country`, `address_postalcode`, `deleted`, `portal_only`, `show_on_employees`, `employee_status`, `messenger_id`, `messenger_type`, `reports_to_id`, `is_group`, `factor_auth`, `factor_auth_interface`) VALUES
('seed_chris_id', 'chris', '$1$cKwOK0dV$yW07G7NZgamhBWKJWJyti/', 0, NULL, NULL, 1, 'Chris', 'Olliver', 0, 0, 1, NULL, '2018-03-08 13:56:40', '2018-03-08 13:56:40', '1', '1', 'Senior Account Rep', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 'Active', NULL, NULL, 'seed_will_id', 0, NULL, NULL),
('seed_jim_id', 'jim', '$1$4XnI1J1B$8xZvxT2b1zR58KMGmvlog1', 0, NULL, NULL, 1, 'Jim', 'Brennan', 0, 0, 1, NULL, '2018-03-08 13:56:40', '2018-03-08 13:56:40', '1', '1', 'VP Sales', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 'Active', NULL, NULL, NULL, 0, NULL, NULL),
('seed_max_id', 'max', '$1$jmbuccD4$P2gfGxdscBHfewEA8TR8R0', 0, NULL, NULL, 1, 'Max', 'Jensen', 0, 0, 1, NULL, '2018-03-08 13:56:40', '2018-03-08 13:56:40', '1', '1', 'Account Rep', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 'Active', NULL, NULL, 'seed_sarah_id', 0, NULL, NULL),
('seed_sally_id', 'sally', '$1$voidBDsT$vGAwyWGIQ2DZYFdRAGIhM.', 0, NULL, NULL, 1, 'Sally', 'Bronsen', 0, 0, 1, NULL, '2018-03-08 13:56:40', '2018-03-08 13:56:40', '1', '1', 'Senior Account Rep', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 'Active', NULL, NULL, 'seed_sarah_id', 0, NULL, NULL),
('seed_sarah_id', 'sarah', '$1$jeiDHrFf$bD2QW92ko8QEzUIIVLDmU0', 0, NULL, NULL, 1, 'Sarah', 'Smith', 0, 0, 1, NULL, '2018-03-08 13:56:40', '2018-03-08 13:56:40', '1', '1', 'Sales Manager West', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 'Active', NULL, NULL, 'seed_jim_id', 0, NULL, NULL),
('seed_will_id', 'will', '$1$vTEOOVk0$eztWU/28R6K8lYyJ6061X0', 0, NULL, NULL, 1, 'Will', 'Westin', 0, 0, 1, NULL, '2018-03-08 13:56:40', '2018-03-08 13:56:40', '1', '1', 'Sales Manager East', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 'Active', NULL, NULL, 'seed_jim_id', 0, NULL, NULL);
INSERT INTO `emails` (`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `deleted`, `assigned_user_id`, `orphaned`, `last_synced`, `date_sent_received`, `message_id`, `type`, `status`, `flagged`, `reply_to_status`, `intent`, `mailbox_id`, `parent_type`, `parent_id`, `uid`, `category_id`) VALUES ('eae65b87-6852-e43c-4213-5b213b39f2aa', 'TestEmail1', NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pick', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `emails` (`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `deleted`, `assigned_user_id`, `orphaned`, `last_synced`, `date_sent_received`, `message_id`, `type`, `status`, `flagged`, `reply_to_status`, `intent`, `mailbox_id`, `parent_type`, `parent_id`, `uid`, `category_id`) VALUES ('eae65b87-6852-e43c-4213-5b213b39f2ab', 'TestEmail2', NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pick', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `emails_text` (`email_id`, `from_addr`, `reply_to_addr`, `to_addrs`, `cc_addrs`, `bcc_addrs`, `description`, `description_html`, `raw_source`, `deleted`) VALUES ('eae65b87-6852-e43c-4213-5b213b39f2aa', NULL, NULL, NULL, NULL, NULL, NULL, '<p>Test Description 1<p>', NULL, '0');
INSERT INTO `emails_text` (`email_id`, `from_addr`, `reply_to_addr`, `to_addrs`, `cc_addrs`, `bcc_addrs`, `description`, `description_html`, `raw_source`, `deleted`) VALUES ('eae65b87-6852-e43c-4213-5b213b39f2ab', NULL, NULL, NULL, NULL, NULL, NULL, '<p>Test Description 2<p>', NULL, '0');
