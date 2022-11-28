SET FOREIGN_KEY_CHECKS = 0;

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

INSERT INTO `chronopost_pickup_point_delivery_mode_i18n`
SELECT dm.id, 'fr_FR', dm.title
FROM `chronopost_pickup_point_delivery_mode` dm;

INSERT INTO `chronopost_pickup_point_delivery_mode_i18n`
SELECT dm.id, 'en_US', dm.title
FROM `chronopost_pickup_point_delivery_mode` dm;

ALTER TABLE `chronopost_pickup_point_delivery_mode`
DROP COLUMN `title`;

SET FOREIGN_KEY_CHECKS = 1;