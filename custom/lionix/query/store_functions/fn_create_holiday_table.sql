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

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-01-01', '2016-01-01', DATE_FORMAT('2016-01-01','%W'), 'Año Nuevo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-04-11', '2016-04-11', DATE_FORMAT('2016-04-11','%W'), 'Día de Juan Santamaría', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-05-01', '2016-05-01', DATE_FORMAT('2016-05-01','%W'), 'Día Internacional del Trabajo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-07-25', '2016-07-25', DATE_FORMAT('2016-07-25','%W'), 'Anexión del Partido de Nicoya a Costa Rica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-08-02', '2016-08-02', DATE_FORMAT('2016-08-02','%W'), 'Día de la Virgen de los Ángeles', 'CR'); -- Pago no obligatorio

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-08-15', '2016-08-15', DATE_FORMAT('2016-08-15','%W'), 'Día de la Madre', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-09-15', '2016-09-15', DATE_FORMAT('2016-09-15','%W'), 'Independencia de Costa Rica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-10-12', '2016-10-12', DATE_FORMAT('2016-10-12','%W'), 'Día de las Culturas', 'CR'); -- Pago no obligatorio

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2016-12-25', '2016-12-25', DATE_FORMAT('2016-12-25','%W'), 'Navidad', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-01-01', '2017-01-01', DATE_FORMAT('2017-01-01','%W'), 'Año Nuevo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-04-11', '2017-04-11', DATE_FORMAT('2017-04-11','%W'), 'Día de Juan Santamaría', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-05-01', '2017-05-01', DATE_FORMAT('2017-05-01','%W'), 'Día Internacional del Trabajo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-07-25', '2017-07-25', DATE_FORMAT('2017-07-25','%W'), 'Anexión del Partido de Nicoya a Costa Rica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-08-02', '2017-08-02', DATE_FORMAT('2017-08-02','%W'), 'Día de la Virgen de los Ángeles', 'CR'); -- Pago no obligatorio

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-08-15', '2017-08-15', DATE_FORMAT('2017-08-15','%W'), 'Día de la Madre', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-09-15', '2017-09-15', DATE_FORMAT('2017-09-15','%W'), 'Independencia de Costa Rica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-10-12', '2017-10-12', DATE_FORMAT('2017-10-12','%W'), 'Día de las Culturas', 'CR'); -- Pago no obligatorio

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2017-12-25', '2017-12-25', DATE_FORMAT('2017-12-25','%W'), 'Navidad', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-01-01', '2018-01-01', DATE_FORMAT('2018-01-01','%W'), 'Año Nuevo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-04-11', '2018-04-11', DATE_FORMAT('2018-04-11','%W'), 'Día de Juan Santamaría', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-05-01', '2018-05-01', DATE_FORMAT('2018-05-01','%W'), 'Día Internacional del Trabajo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-07-25', '2018-07-25', DATE_FORMAT('2018-07-25','%W'), 'Anexión del Partido de Nicoya a Costa Rica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-08-02', '2018-08-02', DATE_FORMAT('2018-08-02','%W'), 'Día de la Virgen de los Ángeles', 'CR'); -- Pago no obligatorio

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-08-15', '2018-08-15', DATE_FORMAT('2018-08-15','%W'), 'Día de la Madre', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-09-15', '2018-09-15', DATE_FORMAT('2018-09-15','%W'), 'Independencia de Costa Rica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-10-12', '2018-10-12', DATE_FORMAT('2018-10-12','%W'), 'Día de las Culturas', 'CR'); -- Pago no obligatorio

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2018-12-25', '2018-12-25', DATE_FORMAT('2018-12-25','%W'), 'Navidad', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-01-01', '2019-01-01', DATE_FORMAT('2019-01-01','%W'), 'Año Nuevo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-04-11', '2019-04-11', DATE_FORMAT('2019-04-11','%W'), 'Día de Juan Santamaría', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-05-01', '2019-05-01', DATE_FORMAT('2019-05-01','%W'), 'Día Internacional del Trabajo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-07-25', '2019-07-25', DATE_FORMAT('2019-07-25','%W'), 'Anexión del Partido de Nicoya a Costa Rica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-08-02', '2019-08-02', DATE_FORMAT('2019-08-02','%W'), 'Día de la Virgen de los Ángeles', 'CR'); -- Pago no obligatorio

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-08-15', '2019-08-15', DATE_FORMAT('2019-08-15','%W'), 'Día de la Madre', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-09-15', '2019-09-15', DATE_FORMAT('2019-09-15','%W'), 'Independencia de Costa Rica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-10-12', '2019-10-12', DATE_FORMAT('2019-10-12','%W'), 'Día de las Culturas', 'CR'); -- Pago no obligatorio

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2019-12-25', '2019-12-25', DATE_FORMAT('2019-12-25','%W'), 'Navidad', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-01-01', '2020-01-01', DATE_FORMAT('2020-01-01','%W'), 'Año Nuevo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-04-11', '2020-04-11', DATE_FORMAT('2020-04-11','%W'), 'Día de Juan Santamaría', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-05-01', '2020-05-01', DATE_FORMAT('2020-05-01','%W'), 'Día Internacional del Trabajo', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-07-25', '2020-07-25', DATE_FORMAT('2020-07-25','%W'), 'Anexión del Partido de Nicoya a Costa Rica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-08-02', '2020-08-02', DATE_FORMAT('2020-08-02','%W'), 'Día de la Virgen de los Ángeles', 'CR'); -- Pago no obligatorio

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-08-15', '2020-08-15', DATE_FORMAT('2020-08-15','%W'), 'Día de la Madre', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-09-15', '2020-09-15', DATE_FORMAT('2020-09-15','%W'), 'Independencia de Costa Rica', 'CR');

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-10-12', '2020-10-12', DATE_FORMAT('2020-10-12','%W'), 'Día de las Culturas', 'CR'); -- Pago no obligatorio

INSERT INTO `holiday_table` (`holiday_table_id`, `holiday_date`, `week_day`, `holiday_name`, `Country_codes`)
VALUES ('2020-12-25', '2020-12-25', DATE_FORMAT('2020-12-25','%W'), 'Navidad', 'CR');
