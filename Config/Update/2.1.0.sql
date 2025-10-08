SET FOREIGN_KEY_CHECKS = 0;
UPDATE `module` SET `category` = 'delivery' where `code` = 'ChronopostPickupPoint';
SET FOREIGN_KEY_CHECKS = 1;
