-- Fix permissions for Simple Packages module
-- This adds the module to OSPOS's permission system

-- Add the module to the modules table
INSERT INTO `ospos_modules` (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`) 
VALUES ('module_simple_packages', 'module_simple_packages_desc', 35, 'simple_packages')
ON DUPLICATE KEY UPDATE `sort` = 35;

-- Add the permission to the permissions table first
INSERT INTO `ospos_permissions` (`permission_id`, `module_id`, `location_id`) 
VALUES ('simple_packages', 'simple_packages', NULL)
ON DUPLICATE KEY UPDATE `module_id` = 'simple_packages';

-- Add permissions for all employees (you can modify this as needed)
INSERT INTO `ospos_grants` (`permission_id`, `person_id`, `menu_group`) 
SELECT 'simple_packages', `person_id`, 'home' 
FROM `ospos_employees` 
WHERE `deleted` = 0
ON DUPLICATE KEY UPDATE `menu_group` = 'home';

-- If you want to add it to specific employees only, use this instead:
-- INSERT INTO `ospos_grants` (`permission_id`, `person_id`, `menu_group`) 
-- VALUES ('simple_packages', 1, 'home');  -- Replace 1 with actual employee ID
