-- Create tables for Simple Packages system
-- This is a simplified alternative to the complex item kits system

-- Table for packages
CREATE TABLE IF NOT EXISTS `ospos_simple_packages` (
  `package_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `package_number` varchar(255) DEFAULT NULL,
  `description` text,
  `total_price` decimal(15,2) DEFAULT 0.00,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`package_id`),
  UNIQUE KEY `package_number` (`package_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table for items within packages
CREATE TABLE IF NOT EXISTS `ospos_simple_package_items` (
  `package_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` decimal(15,3) NOT NULL DEFAULT 1,
  PRIMARY KEY (`package_id`,`item_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `simple_package_items_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `ospos_simple_packages` (`package_id`) ON DELETE CASCADE,
  CONSTRAINT `simple_package_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insert sample data for testing
INSERT INTO `ospos_simple_packages` (`name`, `package_number`, `description`, `total_price`, `active`) VALUES
('Sample Package 1', 'PKG001', 'A sample package for testing', 0.00, 1),
('Sample Package 2', 'PKG002', 'Another sample package', 0.00, 1);
