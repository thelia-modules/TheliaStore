
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- store_extension
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `store_extension`;

CREATE TABLE `store_extension`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(55),
    `extension_id` INTEGER NOT NULL,
    `product_extension_id` INTEGER NOT NULL,
    `extension_name` VARCHAR(255),
    `token` VARCHAR(255),
    `installation_state` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `store_extension_U_1` (`extension_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- store_config
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `store_config`;

CREATE TABLE `store_config`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `api_token` VARCHAR(255),
    `api_key` VARCHAR(255),
    `api_url` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- insert default values
-- TODO : delete this
INSERT INTO `store_config` (`api_token`, `api_key`, `api_url`) VALUES
('100FBFED0B742F288013F1ED1','64285C2A60E9F941A7B8EB868A918032C07CDD0C1DD184FB','http://thelia-marketplace.openstudio-lab.com');

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
