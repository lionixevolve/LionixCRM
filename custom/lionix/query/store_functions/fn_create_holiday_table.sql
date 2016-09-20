-- Slightly changed it version, that includes saturdays, took it from here:
-- http://mgw.dumatics.com/mysql-function-to-calculate-elapsed-working-time/
CREATE TABLE `holiday_table` (
    `holiday_table_id` varchar(10) NOT NULL,
    `holiday_date` DATETIME NULL DEFAULT NULL,
    `week_day` VARCHAR(12) NULL DEFAULT NULL,
    `holiday_name` VARCHAR(45) NULL DEFAULT NULL,
    `Country_codes` VARCHAR(45) NOT NULL DEFAULT 'ALL',
    PRIMARY KEY (`holiday_table_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
