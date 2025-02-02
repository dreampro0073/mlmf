<?php 

// Devendra 20aug2023

CREATE TABLE `plans` ( `id` INT NOT NULL AUTO_INCREMENT , `principal_amount` DOUBLE NULL DEFAULT NULL , `interest_rate` DOUBLE NULL DEFAULT NULL , `plan_name` VARCHAR(255) NULL DEFAULT NULL , `emi_type` VARCHAR(255) NULL DEFAULT NULL , `time_line` INT NULL DEFAULT '0' , `interest_amount` DOUBLE NULL DEFAULT NULL , `no_of_emis` INT NULL DEFAULT '0' , `created_at` TIMESTAMP NULL DEFAULT NULL , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `emi_types` ( `id` INT NOT NULL AUTO_INCREMENT , `type_name` VARCHAR(255) NULL DEFAULT NULL , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `days` ( `id` INT NOT NULL AUTO_INCREMENT , `day_name` INT NULL DEFAULT NULL , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `days` CHANGE `day_name` `day_name` VARCHAR(30) NULL DEFAULT NULL;

// Dipanshu 6 sep

ALTER TABLE `group_clients` CHANGE `client_id` `customer_id` INT(11) NULL DEFAULT NULL;
RENAME TABLE `group_clients` TO `group_customers`;

//Dipanshu 8 Sep 2023

CREATE TABLE `group_emi_dates` ( `id` INT NOT NULL , `group_id` INT NOT NULL DEFAULT '0' , `emi_date` DATE NULL DEFAULT NULL , `status` INT NOT NULL DEFAULT '0' , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_at` TIMESTAMP NULL DEFAULT NULL ) ENGINE = InnoDB;

ALTER TABLE `groups` ADD `start_date` DATE NULL DEFAULT NULL AFTER `plan_id`;


//Dipanshu 14th Sep 2023

CREATE TABLE `emi_collection` ( `id` INT NOT NULL AUTO_INCREMENT , `group_id` INT NOT NULL DEFAULT '0' , `group_emi_date_id` INT NOT NULL DEFAULT '0' , `customer_id` INT NOT NULL DEFAULT '0' , `collected_amount` INT NOT NULL DEFAULT '0' , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `emi_collection` CHANGE `collected_amount` `collected_amount` VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE `emi_collection` ADD `status` TINYINT NOT NULL DEFAULT '0' AFTER `id`;

//Devendra 18th Sep 2023

ALTER TABLE `emi_collection` ADD `penalty` DOUBLE NULL DEFAULT NULL AFTER `created_at`, ADD `remark` VARCHAR(255) NOT NULL AFTER `penalty`;

//Devendra 19th Sep 2023
ALTER TABLE `group_emi_dates` ADD `emi_status` TINYINT NOT NULL DEFAULT '0' AFTER `emi_date`;
ALTER TABLE `plans` CHANGE `principal_amount` `principal_amount` DOUBLE NULL DEFAULT NULL;
ALTER TABLE `plans` ADD `emi_amount` DOUBLE NULL DEFAULT NULL AFTER `no_of_emis`;


// ALTER TABLE `emi_collection` ADD `emi_amount` VARCHAR(255) NULL DEFAULT NULL AFTER `collected_amount`, ADD `interest_payment` VARCHAR(255) NULL DEFAULT NULL AFTER `emi_amount`, ADD `principal_payment` VARCHAR(255) NULL DEFAULT NULL AFTER `interest_payment`;
ALTER TABLE `group_emi_dates` ADD `emi_amount` VARCHAR(255) NULL DEFAULT NULL AFTER `status`, ADD `interest_payment` VARCHAR(255) NULL DEFAULT NULL AFTER `emi_amount`, ADD `principal_payment` VARCHAR(255) NULL DEFAULT NULL AFTER `interest_payment`;

// Devendra 26sep2023

ALTER TABLE `customer_guarantor` ADD `created_at` TIMESTAMP NULL DEFAULT NULL AFTER `address`, ADD `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`;

ALTER TABLE `customers` ADD `father_husband_name` VARCHAR(255) NULL DEFAULT NULL AFTER `name`;

//Dipandhu 30th Sep
ALTER TABLE `users` ADD `enc_id` VARCHAR(255) NULL DEFAULT NULL AFTER `id`;
ALTER TABLE `customers` ADD `enc_id` VARCHAR(255) NULL DEFAULT NULL AFTER `id`;

//Devendra 1st Oct 2023
ALTER TABLE `groups` ADD `pin_code` INT NULL DEFAULT NULL AFTER `village_id`;

UPDATE `emi_types` SET `for_user` = '1' WHERE `emi_types`.`id` = 1;
UPDATE `emi_types` SET `for_user` = '1' WHERE `emi_types`.`id` = 2;
UPDATE `emi_types` SET `for_user` = '1' WHERE `emi_types`.`id` = 3;

ALTER TABLE `groups` ADD `secound_date` DATE NULL DEFAULT NULL AFTER `start_date`;


//Dipanshu Chauhan
ALTER TABLE `customers` ADD `bank_name` VARCHAR(255) NULL DEFAULT NULL AFTER `village_id`, ADD `ifsc_code` VARCHAR(255) NULL DEFAULT NULL AFTER `bank_name`, ADD `ac_no` VARCHAR(255) NULL DEFAULT NULL AFTER `ifsc_code`;


ALTER TABLE `customer_guarantor` ADD `bank_name` VARCHAR(255) NULL DEFAULT NULL AFTER `address`, ADD `ifsc_code` VARCHAR(255) NULL DEFAULT NULL AFTER `bank_name`, ADD `ac_no` VARCHAR(255) NULL DEFAULT NULL AFTER `ifsc_code`;

ALTER TABLE `groups` CHANGE `secound_date` `second_date` DATE NULL DEFAULT NULL;

//DIpanshu Chauhan 3rd Oct
ALTER TABLE `customer_documents` ADD `voter_id` VARCHAR(255) NULL DEFAULT NULL AFTER `pan_card`;

ALTER TABLE `customers` ADD `voter_id_no` VARCHAR(255) NULL DEFAULT NULL AFTER `aadhaar_no`;

ALTER TABLE `customer_guarantor` ADD `voter_id_no` VARCHAR(255) NULL DEFAULT NULL AFTER `aadhaar_no`;

ALTER TABLE `customer_guarantor` ADD `voter_id_card` VARCHAR(255) NULL DEFAULT NULL AFTER `aadhaar_card`;

ALTER TABLE `customer_documents` CHANGE `voter_id` `voter_id_card` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

ALTER TABLE `customers` ADD `marita_status` INT NOT NULL DEFAULT '0' AFTER `name`;

// Dipanshu chauhan
ALTER TABLE `groups` ADD `active` INT NOT NULL DEFAULT '0' AFTER `status`;
ALTER TABLE `customer_documents` ADD `bank_passbook` VARCHAR(255) NULL DEFAULT NULL AFTER `customer_photo`;
ALTER TABLE `customer_documents` ADD `joint_photo` VARCHAR(255) NULL DEFAULT NULL AFTER `bank_passbook`;

ALTER TABLE `customers` ADD `unique_id` VARCHAR(255) NULL DEFAULT NULL AFTER `enc_id`;

ALTER TABLE `customers` ADD `bank_id` INT NULL DEFAULT NULL AFTER `bank_name`;
ALTER TABLE `customer_guarantor` ADD `bank_id` INT NULL DEFAULT NULL AFTER `bank_name`;
ALTER TABLE `customers` CHANGE `marita_status` `marital_status` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `group_customers` ADD `purpose` TEXT NULL DEFAULT NULL AFTER `customer_id`;
ALTER TABLE `groups` ADD `insurance_fee` VARCHAR(20) NULL DEFAULT NULL AFTER `pin_code`, ADD `processing_fee` VARCHAR(20) NULL DEFAULT NULL AFTER `insurance_fee`;

// Devendra 11Oct2023
ALTER TABLE `emi_collection` ADD `penalty_amount` VARCHAR(20) NULL DEFAULT NULL AFTER `collected_amount`, ADD `remark` TEXT NULL DEFAULT NULL AFTER `penalty_amount`;

// Devendra 30 Oct2023

ALTER TABLE `group_customers` ADD `closed` TINYINT NOT NULL DEFAULT '0' AFTER `status`;

// Devendra 04Dec2023
ALTER TABLE `customers` ADD `processing_status` TINYINT NOT NULL DEFAULT '1' AFTER `status`;
ALTER TABLE `customers` CHANGE `processing_status` `processing_status` TINYINT(4) NOT NULL DEFAULT '2';

CREATE TABLE `emi_balence` ( `id` INT NOT NULL , `emi_collection_id` INT NOT NULL DEFAULT '0' , `emi_amount` VARCHAR(255) NULL DEFAULT NULL , `collected_amount` VARCHAR(255) NULL DEFAULT NULL , `balance_amount` VARCHAR(255) NULL DEFAULT NULL , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_at` TIMESTAMP NULL DEFAULT NULL ) ENGINE = InnoDB;
ALTER TABLE `emi_balence` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `emi_balence` ADD `customer_id` INT NULL DEFAULT NULL AFTER `emi_collection_id`;
ALTER TABLE `emi_balence` ADD `collection_status` TINYINT(2) NOT NULL DEFAULT '0' AFTER `balance_amount`;

// 20 Feb 2024

CREATE TABLE `banking` ( `id` INT NOT NULL , `added_by` INT NULL DEFAULT NULL , `amount` DOUBLE NULL DEFAULT NULL , `type` INT NULL DEFAULT NULL COMMENT '1=>Expense, 2=>Income' , `created_at` TIMESTAMP NULL DEFAULT NULL , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE = InnoDB;
ALTER TABLE `banking` ADD `transaction_type` TINYINT(2) NULL DEFAULT NULL COMMENT '1=>UPI, 2=>Cash' AFTER `type`;
ALTER TABLE `banking` ADD `sent_received_by` VARCHAR(255) NULL DEFAULT NULL AFTER `transaction_type`;
ALTER TABLE `banking` ADD `remarks` VARCHAR(255) NULL DEFAULT NULL AFTER `sent_received_by`;
ALTER TABLE `banking` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `banking` ADD `invoice` VARCHAR(255) NULL DEFAULT NULL AFTER `sent_received_by`;
ALTER TABLE `group_customers` ADD `invoice` VARCHAR(255) NULL DEFAULT NULL AFTER `closed`;

// 09 Apr 2024

ALTER TABLE `banking` ADD `date` DATE NULL DEFAULT NULL AFTER `sent_received_by`;

// 04Jan2025

CREATE TABLE `client_id` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(255) NULL DEFAULT NULL , `client_name` VARCHAR(255) NULL DEFAULT NULL , `email` VARCHAR(255) NULL DEFAULT NULL , `mobile` VARCHAR(20) NULL DEFAULT NULL , `status` TINYINT(2) NOT NULL DEFAULT '1' COMMENT '1=>active client' , `gst` VARCHAR(30) NULL DEFAULT NULL , `address` VARCHAR(255) NULL DEFAULT NULL , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB; 
ALTER TABLE `banking` ADD `client_id` INT NULL DEFAULT NULL AFTER `added_by`;
ALTER TABLE `customers` ADD `client_id` INT NULL DEFAULT NULL AFTER `ac_no`;
ALTER TABLE `emi_balence` ADD `client_id` INT NULL DEFAULT NULL AFTER `collection_status`;
ALTER TABLE `emi_collection` ADD `client_id` INT NULL DEFAULT NULL AFTER `remark`;
ALTER TABLE `expenses` ADD `added_by` INT NULL DEFAULT NULL AFTER `vendor`, ADD `client_id` INT NULL DEFAULT NULL AFTER `added_by`;
ALTER TABLE `groups` ADD `client_id` INT NULL DEFAULT NULL AFTER `active`;
ALTER TABLE `group_customers` ADD `client_id` INT NULL DEFAULT NULL AFTER `invoice`;
ALTER TABLE `incomes` ADD `added_by` INT NULL DEFAULT NULL AFTER `income_file`, ADD `client_id` INT NULL DEFAULT NULL AFTER `added_by`;
ALTER TABLE `plans` ADD `client_id` INT NULL DEFAULT NULL AFTER `status`;
ALTER TABLE `users` ADD `client_id` INT NULL DEFAULT NULL AFTER `last_login`;

// One time Run 
UPDATE groups set client_id = 1;
update `plans` set client_id = 1;
update `customers`  set client_id = 1;
UPDATE banking set client_id = 1;
UPDATE emi_balence set client_id = 1;
UPDATE emi_collection set client_id = 1;
UPDATE emi_collection set client_id = 1;
UPDATE expenses set client_id = 1;
UPDATE group_customers set client_id = 1;
UPDATE incomes set client_id = 1;
UPDATE users set client_id = 1;
INSERT INTO `seasons` (`year`, `Season`) VALUES ('2023', '2023-24'), ('2024', '2024-25'),('2025', '2025-20');
?>