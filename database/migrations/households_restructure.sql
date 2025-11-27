-- =====================================================
-- Household Table Restructure Migration
-- =====================================================
-- This migration restructures the households table to match
-- the new schema with household_no, address, purok, and head_resident_id
-- 
-- WARNING: This will drop the existing households table and create a new one.
-- Make sure to backup your data before running this migration!
-- =====================================================

-- Drop existing households table (BACKUP YOUR DATA FIRST!)
DROP TABLE IF EXISTS households;

-- Create new households table with updated structure
CREATE TABLE households (
    household_id INT AUTO_INCREMENT PRIMARY KEY,
    head_resident_id INT NULL COMMENT 'FK to residents.id, updated after head insertion',
    household_no VARCHAR(50) NOT NULL UNIQUE,
    address VARCHAR(255) NOT NULL,
    purok VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_household_no (household_no),
    INDEX idx_head_resident_id (head_resident_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Note: Foreign key constraint for head_resident_id can be added after residents table is created:
-- ALTER TABLE households 
-- ADD CONSTRAINT fk_households_head_resident 
-- FOREIGN KEY (head_resident_id) REFERENCES residents(id) 
-- ON DELETE SET NULL ON UPDATE CASCADE;
