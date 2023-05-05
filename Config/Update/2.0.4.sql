SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `chronopost_pickup_point_order` ADD `id_relais` VARCHAR(255) AFTER `delivery_code`;

SET FOREIGN_KEY_CHECKS = 1;