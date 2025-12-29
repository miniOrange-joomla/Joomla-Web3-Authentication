
CREATE TABLE IF NOT EXISTS `#__miniorange_web3_customer_details` (
`id` int(11) UNSIGNED NOT NULL,
`email` VARCHAR(255)  NOT NULL ,
`password` VARCHAR(255)  NOT NULL ,
`admin_phone` VARCHAR(255)  NOT NULL ,
`customer_key` VARCHAR(255)  NOT NULL ,
`customer_token` VARCHAR(255) NOT NULL,
`api_key` VARCHAR(255)  NOT NULL,
`login_status` tinyint(1) DEFAULT FALSE,
`registration_status` VARCHAR(255) NOT NULL,
`status` VARCHAR(255) NOT NULL,
`new_registration`BOOLEAN NOT NULL,
`transaction_id` VARCHAR(255) NOT NULL,
`email_count` int(11),
`sms_count` int(11),
`email_error` VARCHAR(355),
`metadata_url`  VARCHAR(255) NOT NULL,
`admin_email` VARCHAR(255)  NOT NULL ,
`mo_cron_period` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__miniorange_web3_proxy_setup` (
`id` INT(11) UNSIGNED NOT NULL ,
`password` VARCHAR(255) NOT NULL ,
`proxy_host_name` VARCHAR(255) NOT NULL ,
`port_number` VARCHAR(255) NOT NULL ,
`username` VARCHAR(255) NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__miniorange_web3_config_settings` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`enable_metamask_user_login` tinyint default 0,
`enable_wallet_connect_user_login` tinyint default 0,
`enable_coinbase_user_login` tinyint default 0,
`enable_myalgo_user_login` tinyint default 0,
`enable_phantom_user_login` tinyint default 0,
`enable_web3_user_login` VARCHAR(255)  NOT NULL,

`uninstall_feedback` int(2)  NOT NULL,
`usrlmt` VARCHAR(255) DEFAULT 'MTAK',
`userslim` VARCHAR(255) DEFAULT 'MAo=',
`test_configuration` boolean DEFAULT false,
`sso_status` boolean DEFAULT false,
`sso_var` VARCHAR(255) DEFAULT 'NjAK',
`sso_test` VARCHAR(255) DEFAULT 'MAo=',
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__miniorange_web3_transient_details` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`transient_id` VARCHAR(255) NOT NULL,
`transient_option` VARCHAR(255) NOT NULL,
`transient_timeout` VARCHAR(255) NOT NULL,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

INSERT IGNORE INTO `#__miniorange_web3_proxy_setup`(`id`) values (1);
INSERT IGNORE INTO `#__miniorange_web3_customer_details`(`id`,`login_status`) values (1,0);
INSERT IGNORE INTO `#__miniorange_web3_transient_details`(`id`) values (1);
INSERT IGNORE INTO `#__miniorange_web3_config_settings`(`id`) values (1);
