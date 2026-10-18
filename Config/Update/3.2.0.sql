SET FOREIGN_KEY_CHECKS = 0;

-- The module's mail layouts no longer share their name with the shipping mail of the email theme, which wins over a module's file of the same name.
UPDATE `message`
SET `html_layout_file_name` = 'chronopost_pickup_point_order_shipped.html',
    `text_layout_file_name` = 'chronopost_pickup_point_order_shipped.txt'
WHERE `name` = 'chronopost_pickup_point_confirmation_message_name'
  AND `html_layout_file_name` = 'order_shipped.html'
  AND `text_layout_file_name` = 'order_shipped.txt';

SET FOREIGN_KEY_CHECKS = 1;
