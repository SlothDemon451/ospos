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

