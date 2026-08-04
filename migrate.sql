-- New migration (v4.0.1)
ALTER TABLE `settings` ADD `site_logo_light` LONGTEXT NULL AFTER `site_logo`;

-- New migration (v4.0.0)
INSERT INTO `configs` (`config_key`, `config_value`) VALUES ('activation_email_address', NULL);

-- New migration (v3.0.0)
INSERT INTO `configs` (`config_key`, `config_value`) VALUES ('toyyibpay_mode', 'live');
INSERT INTO `configs` (`config_key`, `config_value`) VALUES ('toyyibpay_api_key', 'YOUR_TOYYIBPAY_API_KEY'), ('toyyibpay_category_code', 'YOUR_TOYYIBPAY_CATEGORY_CODE');
INSERT INTO `gateways` (`logo`, `name`, `display_name`, `client_id`, `secret_key`) VALUES ('img/payments/toyyibpay.png', 'Toyyibpay', 'Toyyibpay', '22', '23');
INSERT INTO `configs` (`config_key`, `config_value`) VALUES ('flw_public_key', 'YOUR_FLW_PUBLIC_KEY'), ('flw_secret_key', 'YOUR_FLW_SECRET_KEY'), ('flw_encryption_key', 'YOUR_FLW_ENCRYPTION_KEY');
INSERT INTO `gateways` (`logo`, `name`, `display_name`, `client_id`, `secret_key`) VALUES ('img/payments/flutterwave.png', 'Flutterwave', 'Flutterwave', '24', '25');

-- New migration (v2.0.0)
UPDATE `configs` SET `config_key` = 'phonepe_client_id' WHERE `configs`.`id` = 54;
UPDATE `configs` SET `config_key` = 'phonepe_client_secret' WHERE `configs`.`id` = 55;

INSERT INTO `configs` (`config_key`, `config_value`) VALUES ('phonepe_client_version', '1');
INSERT INTO `configs` (`config_key`, `config_value`) VALUES ('demo_mode', '0');
INSERT INTO `configs` (`config_key`, `config_value`) VALUES ('cronjob_dates_in_array', '[10, 5, 3, 1]');
INSERT INTO `configs` (`config_key`, `config_value`) VALUES ('cron_hour', '10');
INSERT INTO `configs` (`config_key`, `config_value`) VALUES ('date_time_format', 'M d, Y h:i A');
INSERT INTO `configs` (`config_key`, `config_value`) VALUES ('currency_format_type', '1,234,567.89');
INSERT INTO `configs` (`config_key`, `config_value`) VALUES ('currency_decimals_place', '2');

CREATE TABLE `backups` (
  `id` int(10) UNSIGNED NOT NULL,
  `backup_id` varchar(191) NOT NULL,
  `type` varchar(191) NOT NULL DEFAULT 'file',
  `version` varchar(191) NOT NULL,
  `file_name` varchar(191) NOT NULL,
  `path` longtext NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `backups` ADD PRIMARY KEY (`id`);

ALTER TABLE `backups` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;