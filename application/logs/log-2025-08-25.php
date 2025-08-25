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
