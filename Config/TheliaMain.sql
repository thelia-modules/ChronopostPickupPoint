
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- chronopost_pickup_point_order
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `chronopost_pickup_point_order`;

CREATE TABLE `chronopost_pickup_point_order`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `order_id` INTEGER NOT NULL,
    `delivery_type` TEXT,
    `delivery_code` TEXT,
    `label_directory` TEXT,
    `label_number` TEXT,
    `id_relais` VARCHAR(255),
    PRIMARY KEY (`id`),
    INDEX `fi_chronopost_pickup_point_order_order_id` (`order_id`),
    CONSTRAINT `fk_chronopost_pickup_point_order_order_id`
        FOREIGN KEY (`order_id`)
        REFERENCES `order` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- chronopost_pickup_point_delivery_mode
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `chronopost_pickup_point_delivery_mode`;

CREATE TABLE `chronopost_pickup_point_delivery_mode`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(55) NOT NULL,
    `freeshipping_active` TINYINT(1),
    `freeshipping_from` FLOAT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- chronopost_pickup_point_price
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `chronopost_pickup_point_price`;

CREATE TABLE `chronopost_pickup_point_price`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `area_id` INTEGER NOT NULL,
    `delivery_mode_id` INTEGER NOT NULL,
    `weight_max` FLOAT,
    `price_max` FLOAT,
    `franco_min_price` FLOAT,
    `price` FLOAT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fi_chronopost_pickup_point_price_area_id` (`area_id`),
    INDEX `fi_chronopost_pickup_point_price_delivery_mode_id` (`delivery_mode_id`),
    CONSTRAINT `fk_chronopost_pickup_point_price_area_id`
        FOREIGN KEY (`area_id`)
        REFERENCES `area` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,
    CONSTRAINT `fk_chronopost_pickup_point_price_delivery_mode_id`
        FOREIGN KEY (`delivery_mode_id`)
        REFERENCES `chronopost_pickup_point_delivery_mode` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- chronopost_pickup_point_area_freeshipping
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `chronopost_pickup_point_area_freeshipping`;

CREATE TABLE `chronopost_pickup_point_area_freeshipping`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `area_id` INTEGER NOT NULL,
    `delivery_mode_id` INTEGER NOT NULL,
    `cart_amount` DECIMAL(16,6) DEFAULT 0.000000,
    PRIMARY KEY (`id`),
    INDEX `fi_chronopost_pickup_point_area_freeshipping_area_id` (`area_id`),
    INDEX `fi_chronopost_pickup_point_area_freeshipping_delivery_mode_id` (`delivery_mode_id`),
    CONSTRAINT `fk_chronopost_pickup_point_area_freeshipping_area_id`
        FOREIGN KEY (`area_id`)
        REFERENCES `area` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,
    CONSTRAINT `fk_chronopost_pickup_point_area_freeshipping_delivery_mode_id`
        FOREIGN KEY (`delivery_mode_id`)
        REFERENCES `chronopost_pickup_point_delivery_mode` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- chronopost_pickup_point_order_address
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `chronopost_pickup_point_order_address`;

CREATE TABLE `chronopost_pickup_point_order_address`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `company` VARCHAR(255),
    `Address1` VARCHAR(255),
    `Address2` VARCHAR(255),
    `Address3` VARCHAR(255),
    `zip_code` VARCHAR(255),
    `city` VARCHAR(255),
    `country_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fi_address_chronopost_pickup_point_country_id` (`country_id`),
    CONSTRAINT `fk_address_chronopost_pickup_point_country_id`
        FOREIGN KEY (`country_id`)
        REFERENCES `country` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- chronopost_pickup_point_delivery_mode_i18n
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `chronopost_pickup_point_delivery_mode_i18n`;

CREATE TABLE `chronopost_pickup_point_delivery_mode_i18n`
(
    `id` INTEGER NOT NULL,
    `locale` VARCHAR(5) DEFAULT 'en_US' NOT NULL,
    `title` VARCHAR(255),
    PRIMARY KEY (`id`,`locale`),
    CONSTRAINT `chronopost_pickup_point_delivery_mode_i18n_fk_f7df28`
        FOREIGN KEY (`id`)
        REFERENCES `chronopost_pickup_point_delivery_mode` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
