<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-08-23 12:58:57 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '2'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 12:58:57 --> Customer::get_stats query failed for customer_id 2 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 12:59:30 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '2'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 12:59:30 --> Customer::get_stats query failed for customer_id 2 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:00:01 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '2'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:00:01 --> Customer::get_stats query failed for customer_id 2 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:01:57 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '4'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:01:57 --> Customer::get_stats query failed for customer_id 4 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:03:07 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '4'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:03:07 --> Customer::get_stats query failed for customer_id 4 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:04:22 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '4'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:04:22 --> Customer::get_stats query failed for customer_id 4 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:05:04 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '4'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:05:04 --> Customer::get_stats query failed for customer_id 4 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:05:52 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '4'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:05:52 --> Customer::get_stats query failed for customer_id 4 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:05:58 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '4'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:05:58 --> Customer::get_stats query failed for customer_id 4 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:06:39 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '4'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:06:39 --> Customer::get_stats query failed for customer_id 4 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:07:20 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '4'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:07:20 --> Customer::get_stats query failed for customer_id 4 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:07:33 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '4'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:07:33 --> Customer::get_stats query failed for customer_id 4 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:08:03 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '4'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:08:03 --> Customer::get_stats query failed for customer_id 4 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:08:26 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '4'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:08:26 --> Customer::get_stats query failed for customer_id 4 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:11:53 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '4'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:11:53 --> Customer::get_stats query failed for customer_id 4 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:12:11 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '4'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:12:11 --> Customer::get_stats query failed for customer_id 4 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:27:55 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:27:55 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:27:55 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:27:58 --> Severity: error --> Exception: Call to undefined method Customer_types::xss_clean() C:\laragon\www\ospos\application\controllers\Customer_types.php 37
ERROR - 2025-08-23 13:28:04 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:28:04 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:28:04 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:28:06 --> Severity: error --> Exception: Call to undefined method Customer_types::xss_clean() C:\laragon\www\ospos\application\controllers\Customer_types.php 37
ERROR - 2025-08-23 13:28:30 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:28:30 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:28:30 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:28:33 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 17:29:54 --> Severity: error --> Exception: Class 'Persons' not found C:\laragon\www\ospos\application\controllers\Customer_types.php 7
ERROR - 2025-08-23 13:29:57 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:29:57 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:29:57 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 17:29:59 --> Severity: error --> Exception: Class 'Persons' not found C:\laragon\www\ospos\application\controllers\Customer_types.php 7
ERROR - 2025-08-23 13:30:03 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:30:03 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:30:03 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 17:30:18 --> Severity: error --> Exception: Class 'Persons' not found C:\laragon\www\ospos\application\controllers\Customer_types.php 7
ERROR - 2025-08-23 13:30:20 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:30:20 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:30:20 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 17:30:46 --> Severity: error --> Exception: Class 'Persons' not found C:\laragon\www\ospos\application\controllers\Customer_types.php 7
ERROR - 2025-08-23 13:30:48 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:30:48 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:30:48 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 17:31:37 --> Severity: error --> Exception: Class 'Persons' not found C:\laragon\www\ospos\application\controllers\Customer_types.php 7
ERROR - 2025-08-23 17:34:25 --> Severity: Warning --> Declaration of Customer_types::view() should be compatible with Secure_Controller::view($data_item_id = -1) C:\laragon\www\ospos\application\controllers\Customer_types.php 38
ERROR - 2025-08-23 13:34:30 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:34:30 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:34:30 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:34:34 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 17:34:41 --> Severity: Warning --> Declaration of Customer_types::view() should be compatible with Secure_Controller::view($data_item_id = -1) C:\laragon\www\ospos\application\controllers\Customer_types.php 38
ERROR - 2025-08-23 13:36:05 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:36:05 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:36:05 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 17:36:08 --> Severity: Warning --> Declaration of Customer_types::view() should be compatible with Secure_Controller::view($data_item_id = -1) C:\laragon\www\ospos\application\controllers\Customer_types.php 38
ERROR - 2025-08-23 13:36:08 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:36:08 --> Could not find the language line "customer_types_description (TBD)"
ERROR - 2025-08-23 13:36:08 --> Could not find the language line "customer_types_new (TBD)"
ERROR - 2025-08-23 13:36:08 --> Could not find the language line "customer_types_new (TBD)"
ERROR - 2025-08-23 17:36:39 --> Severity: Warning --> Declaration of Customer_types::view() should be compatible with Secure_Controller::view($data_item_id = -1) C:\laragon\www\ospos\application\controllers\Customer_types.php 38
ERROR - 2025-08-23 13:36:39 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:36:39 --> Could not find the language line "customer_types_description (TBD)"
ERROR - 2025-08-23 13:36:39 --> Could not find the language line "customer_types_new (TBD)"
ERROR - 2025-08-23 13:36:39 --> Could not find the language line "customer_types_new (TBD)"
ERROR - 2025-08-23 13:50:11 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:50:11 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:50:11 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:50:13 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:50:13 --> Could not find the language line "customer_types_description (TBD)"
ERROR - 2025-08-23 13:50:13 --> Could not find the language line "customer_types_name_required (TBD)"
ERROR - 2025-08-23 13:50:35 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:50:35 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:50:35 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:51:22 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:51:22 --> Could not find the language line "customer_types_description (TBD)"
ERROR - 2025-08-23 13:51:22 --> Could not find the language line "customer_types_name_required (TBD)"
ERROR - 2025-08-23 13:51:24 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:51:24 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:51:24 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:51:53 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:51:53 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:51:53 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:51:57 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:51:57 --> Could not find the language line "customer_types_description (TBD)"
ERROR - 2025-08-23 13:51:57 --> Could not find the language line "customer_types_name_required (TBD)"
ERROR - 2025-08-23 13:52:02 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:52:02 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:52:02 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:52:27 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:52:27 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:52:27 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:52:32 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:52:53 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:52:53 --> Could not find the language line "customer_types_description (TBD)"
ERROR - 2025-08-23 13:52:53 --> Could not find the language line "customer_types_name_required (TBD)"
ERROR - 2025-08-23 13:52:59 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:52:59 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:52:59 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 13:53:03 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:53:54 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:54:53 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:55:20 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 13:56:10 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '2'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:56:10 --> Customer::get_stats query failed for customer_id 2 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:57:52 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '4'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:57:52 --> Customer::get_stats query failed for customer_id 4 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 13:58:05 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '4'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 13:58:05 --> Customer::get_stats query failed for customer_id 4 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 14:02:45 --> Query error: Unknown column 'sales_items_temp.avg_discount' in 'field list' - Invalid query: SELECT SUM(sales_payments.payment_amount - sales_payments.cash_refund) AS total, MIN(sales_payments.payment_amount - sales_payments.cash_refund) AS min, MAX(sales_payments.payment_amount - sales_payments.cash_refund) AS max, AVG(sales_payments.payment_amount - sales_payments.cash_refund) AS average, ROUND(AVG(sales_items_temp.avg_discount), 2) AS avg_discount, ROUND(SUM(sales_items_temp.quantity), 0) AS quantity
FROM `ospos_sales`
JOIN `ospos_sales_payments` AS `sales_payments` ON `ospos_sales`.`sale_id` = `sales_payments`.`sale_id`
JOIN `ospos_sales_items_temp` AS `sales_items_temp` ON `ospos_sales`.`sale_id` = `sales_items_temp`.`sale_id`
WHERE `ospos_sales`.`customer_id` = '2'
AND `ospos_sales`.`sale_status` = 0
GROUP BY `ospos_sales`.`customer_id`
ERROR - 2025-08-23 14:02:45 --> Customer::get_stats query failed for customer_id 2 - DB error: Array
(
    [code] => 1054
    [message] => Unknown column 'sales_items_temp.avg_discount' in 'field list'
)

ERROR - 2025-08-23 14:03:40 --> Could not find the language line "customer_types_name (TBD)"
ERROR - 2025-08-23 14:03:40 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 14:03:40 --> Could not find the language line "customer_types (TBD)"
ERROR - 2025-08-23 14:04:52 --> Could not find the language line "delivery_men_no_delivery_men_to_display (TBD)"
ERROR - 2025-08-23 14:04:52 --> Could not find the language line "delivery_men_confirm_restore (TBD)"
