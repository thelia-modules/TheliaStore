
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

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
