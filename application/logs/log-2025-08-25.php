<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-08-25 13:34:04 --> Severity: Notice --> Undefined index: payment_time C:\laragon\www\ospos\application\views\sales\invoice.php 218
ERROR - 2025-08-25 13:34:04 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '4'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-25 13:34:04 --> Customer::get_stats query failed for customer_id 4 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-25 13:40:19 --> Could not find the language line "delivery_men_no_delivery_men_to_display (TBD)"
ERROR - 2025-08-25 13:40:19 --> Could not find the language line "delivery_men_confirm_restore (TBD)"
ERROR - 2025-08-25 13:47:17 --> Could not find the language line "common_summary (TBD)"
ERROR - 2025-08-25 13:47:17 --> Could not find the language line "common_back (TBD)"
ERROR - 2025-08-25 13:52:10 --> Could not find the language line "common_summary (TBD)"
ERROR - 2025-08-25 13:52:10 --> Could not find the language line "common_back (TBD)"
ERROR - 2025-08-25 13:54:45 --> Could not find the language line "common_back (TBD)"
ERROR - 2025-08-25 13:57:02 --> Could not find the language line "common_summary (TBD)"
ERROR - 2025-08-25 13:57:02 --> Could not find the language line "common_back (TBD)"
ERROR - 2025-08-25 13:57:23 --> Could not find the language line "common_summary (TBD)"
ERROR - 2025-08-25 13:57:23 --> Could not find the language line "common_back (TBD)"
ERROR - 2025-08-25 13:58:49 --> Could not find the language line "common_back (TBD)"
ERROR - 2025-08-25 13:59:07 --> Could not find the language line "common_back (TBD)"
ERROR - 2025-08-25 13:59:23 --> Could not find the language line "common_back (TBD)"
ERROR - 2025-08-25 14:19:55 --> Query error: Unknown column 'category' in 'field list' - Invalid query: SELECT `item_kit_id`, `ospos_item_kits`.`name` as `name`, `item_kit_number`, `ospos_items`.`name` as `item_name`, `ospos_item_kits`.`description`, `ospos_items`.`description` as `item_description`, `ospos_item_kits`.`item_id` as `kit_item_id`, `kit_discount`, `kit_discount_type`, `price_option`, `print_option`, `category`, `supplier_id`, `item_number`, `cost_price`, `unit_price`, `reorder_level`, `receiving_quantity`, `pic_filename`, `allow_alt_description`, `is_serialized`, `ospos_items`.`deleted`, `item_type`, `stock_type`
FROM `ospos_item_kits`
LEFT JOIN `ospos_items` ON `ospos_item_kits`.`item_id` = `ospos_items`.`item_id`
WHERE `item_kit_id` = -1
OR `item_kit_number` = -1
ERROR - 2025-08-25 14:19:55 --> Severity: error --> Exception: Call to a member function num_rows() on bool C:\laragon\www\ospos\application\models\Item_kit.php 112
ERROR - 2025-08-25 14:20:23 --> Query error: Unknown column 'category' in 'field list' - Invalid query: SELECT `item_kit_id`, `ospos_item_kits`.`name` as `name`, `item_kit_number`, `ospos_items`.`name` as `item_name`, `ospos_item_kits`.`description`, `ospos_items`.`description` as `item_description`, `ospos_item_kits`.`item_id` as `kit_item_id`, `kit_discount`, `kit_discount_type`, `price_option`, `print_option`, `category`, `supplier_id`, `item_number`, `cost_price`, `unit_price`, `reorder_level`, `receiving_quantity`, `pic_filename`, `allow_alt_description`, `is_serialized`, `ospos_items`.`deleted`, `item_type`, `stock_type`
FROM `ospos_item_kits`
LEFT JOIN `ospos_items` ON `ospos_item_kits`.`item_id` = `ospos_items`.`item_id`
WHERE `item_kit_id` = -1
OR `item_kit_number` = -1
ERROR - 2025-08-25 14:20:23 --> Severity: error --> Exception: Call to a member function num_rows() on bool C:\laragon\www\ospos\application\models\Item_kit.php 112
ERROR - 2025-08-25 14:20:45 --> Query error: Unknown column 'category' in 'field list' - Invalid query: SELECT `item_kit_id`, `ospos_item_kits`.`name` as `name`, `item_kit_number`, `ospos_items`.`name` as `item_name`, `ospos_item_kits`.`description`, `ospos_items`.`description` as `item_description`, `ospos_item_kits`.`item_id` as `kit_item_id`, `kit_discount`, `kit_discount_type`, `price_option`, `print_option`, `category`, `supplier_id`, `item_number`, `cost_price`, `unit_price`, `reorder_level`, `receiving_quantity`, `pic_filename`, `allow_alt_description`, `is_serialized`, `ospos_items`.`deleted`, `item_type`, `stock_type`
FROM `ospos_item_kits`
LEFT JOIN `ospos_items` ON `ospos_item_kits`.`item_id` = `ospos_items`.`item_id`
WHERE `item_kit_id` = -1
OR `item_kit_number` = -1
ERROR - 2025-08-25 14:20:45 --> Severity: error --> Exception: Call to a member function num_rows() on bool C:\laragon\www\ospos\application\models\Item_kit.php 112
ERROR - 2025-08-25 14:22:31 --> Query error: Unknown column 'category' in 'field list' - Invalid query: SELECT `item_kit_id`, `ospos_item_kits`.`name` as `name`, `item_kit_number`, `ospos_items`.`name` as `item_name`, `ospos_item_kits`.`description`, `ospos_items`.`description` as `item_description`, `ospos_item_kits`.`item_id` as `kit_item_id`, `kit_discount`, `kit_discount_type`, `price_option`, `print_option`, `category`, `supplier_id`, `item_number`, `cost_price`, `unit_price`, `reorder_level`, `receiving_quantity`, `pic_filename`, `allow_alt_description`, `is_serialized`, `ospos_items`.`deleted`, `item_type`, `stock_type`
FROM `ospos_item_kits`
LEFT JOIN `ospos_items` ON `ospos_item_kits`.`item_id` = `ospos_items`.`item_id`
WHERE `item_kit_id` = -1
OR `item_kit_number` = -1
ERROR - 2025-08-25 14:22:31 --> Severity: error --> Exception: Call to a member function num_rows() on bool C:\laragon\www\ospos\application\models\Item_kit.php 128
ERROR - 2025-08-25 14:22:40 --> Query error: Unknown column 'category' in 'field list' - Invalid query: SELECT `item_kit_id`, `ospos_item_kits`.`name` as `name`, `item_kit_number`, `ospos_items`.`name` as `item_name`, `ospos_item_kits`.`description`, `ospos_items`.`description` as `item_description`, `ospos_item_kits`.`item_id` as `kit_item_id`, `kit_discount`, `kit_discount_type`, `price_option`, `print_option`, `category`, `supplier_id`, `item_number`, `cost_price`, `unit_price`, `reorder_level`, `receiving_quantity`, `pic_filename`, `allow_alt_description`, `is_serialized`, `ospos_items`.`deleted`, `item_type`, `stock_type`
FROM `ospos_item_kits`
LEFT JOIN `ospos_items` ON `ospos_item_kits`.`item_id` = `ospos_items`.`item_id`
WHERE `item_kit_id` = -1
OR `item_kit_number` = -1
ERROR - 2025-08-25 14:22:40 --> Severity: error --> Exception: Call to a member function num_rows() on bool C:\laragon\www\ospos\application\models\Item_kit.php 128
ERROR - 2025-08-25 14:24:15 --> Query error: Unknown column 'category' in 'field list' - Invalid query: SELECT `item_kit_id`, `ospos_item_kits`.`name` as `name`, `item_kit_number`, `ospos_items`.`name` as `item_name`, `ospos_item_kits`.`description`, `ospos_items`.`description` as `item_description`, `ospos_item_kits`.`item_id` as `kit_item_id`, `kit_discount`, `kit_discount_type`, `price_option`, `print_option`, `category`, `supplier_id`, `item_number`, `cost_price`, `unit_price`, `reorder_level`, `receiving_quantity`, `pic_filename`, `allow_alt_description`, `is_serialized`, `ospos_items`.`deleted`, `item_type`, `stock_type`
FROM `ospos_item_kits`
LEFT JOIN `ospos_items` ON `ospos_item_kits`.`item_id` = `ospos_items`.`item_id`
WHERE `item_kit_id` = -1
OR `item_kit_number` = -1
ERROR - 2025-08-25 14:24:15 --> Severity: error --> Exception: Call to a member function num_rows() on bool C:\laragon\www\ospos\application\models\Item_kit.php 128
ERROR - 2025-08-25 14:26:42 --> Could not find the language line "item_kits_item_number_duplicate (TBD)"
ERROR - 2025-08-25 14:27:42 --> Query error: Unknown column 'category' in 'field list' - Invalid query: SELECT `item_kit_id`, `ospos_item_kits`.`name` as `name`, `item_kit_number`, `ospos_items`.`name` as `item_name`, `ospos_item_kits`.`description`, `ospos_items`.`description` as `item_description`, `ospos_item_kits`.`item_id` as `kit_item_id`, `kit_discount`, `kit_discount_type`, `price_option`, `print_option`, `category`, `supplier_id`, `item_number`, `cost_price`, `unit_price`, `reorder_level`, `receiving_quantity`, `pic_filename`, `allow_alt_description`, `is_serialized`, `ospos_items`.`deleted`, `item_type`, `stock_type`
FROM `ospos_item_kits`
LEFT JOIN `ospos_items` ON `ospos_item_kits`.`item_id` = `ospos_items`.`item_id`
WHERE `item_kit_id` = '1'
OR `item_kit_number` = '1'
ERROR - 2025-08-25 14:27:42 --> Severity: error --> Exception: Call to a member function num_rows() on bool C:\laragon\www\ospos\application\models\Item_kit.php 128
ERROR - 2025-08-25 14:29:34 --> Query error: Unknown column 'category' in 'field list' - Invalid query: SELECT `item_kit_id`, `ospos_item_kits`.`name` as `name`, `item_kit_number`, `ospos_items`.`name` as `item_name`, `ospos_item_kits`.`description`, `ospos_items`.`description` as `item_description`, `ospos_item_kits`.`item_id` as `kit_item_id`, `kit_discount`, `kit_discount_type`, `price_option`, `print_option`, `category`, `supplier_id`, `item_number`, `cost_price`, `unit_price`, `reorder_level`, `receiving_quantity`, `pic_filename`, `allow_alt_description`, `is_serialized`, `ospos_items`.`deleted`, `item_type`, `stock_type`
FROM `ospos_item_kits`
LEFT JOIN `ospos_items` ON `ospos_item_kits`.`item_id` = `ospos_items`.`item_id`
WHERE `item_kit_id` = '1'
OR `item_kit_number` = '1'
ERROR - 2025-08-25 14:29:34 --> Database query failed in Item_kit::get_info() for item_kit_id: 1
ERROR - 2025-08-25 14:29:42 --> Query error: Unknown column 'category' in 'field list' - Invalid query: SELECT `item_kit_id`, `ospos_item_kits`.`name` as `name`, `item_kit_number`, `ospos_items`.`name` as `item_name`, `ospos_item_kits`.`description`, `ospos_items`.`description` as `item_description`, `ospos_item_kits`.`item_id` as `kit_item_id`, `kit_discount`, `kit_discount_type`, `price_option`, `print_option`, `category`, `supplier_id`, `item_number`, `cost_price`, `unit_price`, `reorder_level`, `receiving_quantity`, `pic_filename`, `allow_alt_description`, `is_serialized`, `ospos_items`.`deleted`, `item_type`, `stock_type`
FROM `ospos_item_kits`
LEFT JOIN `ospos_items` ON `ospos_item_kits`.`item_id` = `ospos_items`.`item_id`
WHERE `item_kit_id` = '1'
OR `item_kit_number` = '1'
ERROR - 2025-08-25 14:29:42 --> Database query failed in Item_kit::get_info() for item_kit_id: 1
ERROR - 2025-08-25 14:32:06 --> Query error: Unknown column 'category' in 'field list' - Invalid query: SELECT `item_kit_id`, `ospos_item_kits`.`name` as `name`, `item_kit_number`, `ospos_items`.`name` as `item_name`, `ospos_item_kits`.`description`, `ospos_items`.`description` as `item_description`, `ospos_item_kits`.`item_id` as `kit_item_id`, `kit_discount`, `kit_discount_type`, `price_option`, `print_option`, `category`, `supplier_id`, `item_number`, `cost_price`, `unit_price`, `reorder_level`, `receiving_quantity`, `pic_filename`, `allow_alt_description`, `is_serialized`, `ospos_items`.`deleted`, `item_type`, `stock_type`
FROM `ospos_item_kits`
LEFT JOIN `ospos_items` ON `ospos_item_kits`.`item_id` = `ospos_items`.`item_id`
WHERE `item_kit_id` = '1'
OR `item_kit_number` = '1'
ERROR - 2025-08-25 14:32:06 --> Database query failed in Item_kit::get_info() for item_kit_id: 1
ERROR - 2025-08-25 14:32:06 --> Database error: {"code":1054,"message":"Unknown column 'category' in 'field list'"}
ERROR - 2025-08-25 14:33:39 --> Query error: Unknown column 'kit_discount_percent' in 'field list' - Invalid query: SELECT `item_kit_id`, `ospos_item_kits`.`name` as `name`, `item_kit_number`, `ospos_item_kits`.`description`, `ospos_item_kits`.`item_id` as `kit_item_id`, `kit_discount_percent` as `kit_discount`, `price_option`, `print_option`
FROM `ospos_item_kits`
WHERE `item_kit_id` = '1'
OR `item_kit_number` = '1'
ERROR - 2025-08-25 14:33:39 --> Database query failed in Item_kit::get_info() for item_kit_id: 1
ERROR - 2025-08-25 14:33:39 --> Database error: {"code":1054,"message":"Unknown column 'kit_discount_percent' in 'field list'"}
ERROR - 2025-08-25 20:17:34 --> 404 Page Not Found: Images/menubar
ERROR - 2025-08-25 20:17:34 --> 404 Page Not Found: Images/menubar
ERROR - 2025-08-25 20:18:06 --> 404 Page Not Found: Images/menubar
ERROR - 2025-08-25 16:25:59 --> Severity: Notice --> Undefined property: Simple_packages::$Simple_package C:\laragon\www\ospos\application\controllers\Simple_packages.php 30
ERROR - 2025-08-25 16:25:59 --> Severity: error --> Exception: Call to a member function search() on null C:\laragon\www\ospos\application\controllers\Simple_packages.php 30
ERROR - 2025-08-25 16:26:29 --> Severity: Notice --> Undefined property: Simple_packages::$Simple_package C:\laragon\www\ospos\application\controllers\Simple_packages.php 31
ERROR - 2025-08-25 16:26:29 --> Severity: error --> Exception: Call to a member function search() on null C:\laragon\www\ospos\application\controllers\Simple_packages.php 31
ERROR - 2025-08-25 16:28:15 --> Severity: error --> Exception: Cannot pass parameter 1 by reference C:\laragon\www\ospos\application\controllers\Simple_packages.php 143
ERROR - 2025-08-25 16:33:20 --> Severity: error --> Exception: Cannot pass parameter 1 by reference C:\laragon\www\ospos\application\controllers\Simple_packages.php 175
ERROR - 2025-08-25 16:36:40 --> Severity: error --> Exception: Cannot pass parameter 1 by reference C:\laragon\www\ospos\application\controllers\Simple_packages.php 175
ERROR - 2025-08-25 16:37:13 --> Severity: error --> Exception: Cannot pass parameter 1 by reference C:\laragon\www\ospos\application\controllers\Simple_packages.php 175
ERROR - 2025-08-25 16:38:47 --> Severity: error --> Exception: Cannot pass parameter 1 by reference C:\laragon\www\ospos\application\controllers\Simple_packages.php 175
ERROR - 2025-08-25 16:47:21 --> Severity: error --> Exception: Cannot pass parameter 1 by reference C:\laragon\www\ospos\application\controllers\Simple_packages.php 175
ERROR - 2025-08-25 16:48:57 --> Query error: Duplicate entry '' for key 'ospos_simple_packages.package_number' - Invalid query: INSERT INTO `ospos_simple_packages` (`name`, `package_number`, `description`, `active`) VALUES ('ThunderJaw', '', '', '1')
ERROR - 2025-08-25 16:54:34 --> Query error: Unknown column 'package_price' in 'field list' - Invalid query: UPDATE `ospos_simple_packages` SET `name` = 'ThunderJaw', `package_number` = '', `description` = '', `package_price` = '220', `discount` = '', `active` = '1'
WHERE `package_id` = '7'
