-- Customer Types Module Setup
-- Run these SQL commands to set up the customer types functionality

-- 1. Create the customer_types table
CREATE TABLE IF NOT EXISTS `ospos_customer_types` (
  `customer_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`customer_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2. Add customer_type_id column to the customers table
ALTER TABLE `ospos_customers` 
ADD COLUMN `customer_type_id` int(11) NULL AFTER `package_id`,
ADD CONSTRAINT `fk_customers_customer_type` 
FOREIGN KEY (`customer_type_id`) REFERENCES `ospos_customer_types` (`customer_type_id`) ON DELETE SET NULL;

-- 3. Insert some default customer types
INSERT INTO `ospos_customer_types` (`name`, `description`) VALUES
('Retail', 'Individual retail customers'),
('Wholesale', 'Business customers with wholesale pricing'),
('VIP', 'Very Important Customers with special privileges'),
('Corporate', 'Large corporate accounts'),
('Government', 'Government and public sector customers');

-- 4. Create index for better performance
CREATE INDEX `idx_customers_customer_type_id` ON `ospos_customers` (`customer_type_id`);
