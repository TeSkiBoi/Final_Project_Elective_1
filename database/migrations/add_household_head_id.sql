-- ============================================
-- Migration: Add household_head_id to households table
-- Purpose: Change household head from text field to FK reference to residents
-- Date: 2025-11-29
-- ============================================

-- Step 1: Add household_head_id column (nullable, as existing households won't have it)
ALTER TABLE `households` 
ADD COLUMN `household_head_id` VARCHAR(20) NULL AFTER `family_no`;

-- Step 2: Make full_name column nullable (for backwards compatibility and new households)
ALTER TABLE `households` 
MODIFY COLUMN `full_name` VARCHAR(150) NULL;

-- Step 3: Add foreign key constraint to link household_head_id to residents
ALTER TABLE `households`
ADD CONSTRAINT `fk_household_head`
FOREIGN KEY (`household_head_id`) REFERENCES `residents`(`resident_id`)
ON DELETE SET NULL
ON UPDATE CASCADE;

-- Step 4: Add index for better query performance
CREATE INDEX `idx_household_head` ON `households`(`household_head_id`);

-- ============================================
-- NOTES:
-- 1. household_head_id is NULL by default
-- 2. If a resident is deleted, household_head_id becomes NULL
-- 3. full_name is kept for backwards compatibility
-- ============================================
