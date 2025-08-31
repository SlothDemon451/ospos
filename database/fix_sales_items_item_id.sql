-- Alternative approach: Use negative numbers for packages instead of changing field types
-- This preserves the existing database schema and foreign key relationships

-- The issue is that item_id is INT but we're trying to store "PKG_7"
-- Instead of changing the field type, we'll use negative numbers for packages
-- Regular items: positive numbers (1, 2, 3, ...)
-- Packages: negative numbers (-1, -2, -3, ...) where -X represents package X

-- This approach:
-- 1. Keeps the existing INT field type
-- 2. Preserves all foreign key relationships
-- 3. Doesn't break existing functionality
-- 4. Allows packages to be stored as negative integers

-- No database changes needed - just code changes to:
-- 1. Convert "PKG_X" to "-X" when saving to database
-- 2. Convert "-X" back to "PKG_X" when reading from database
-- 3. Handle the mapping in the application code

-- This is much safer than changing field types and breaking existing relationships
