-- =====================================================
-- Residents Table Restructure Migration
-- =====================================================
-- This migration restructures the residents table to match
-- the new schema with id, full_name, birthdate, gender, 
-- occupation, income, household_id, relation_to_head
-- 
-- WARNING: This will drop the existing residents table and create a new one.
-- Make sure to backup your data before running this migration!
-- =====================================================

-- Drop existing residents table (BACKUP YOUR DATA FIRST!)
DROP TABLE IF EXISTS residents;

-- Create new residents table with updated structure
CREATE TABLE residents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    birthdate DATE NOT NULL,
    gender ENUM('Male','Female') NOT NULL,
    occupation VARCHAR(100),
    income DECIMAL(12,2) DEFAULT 0.00,
    household_id INT NOT NULL,
    relation_to_head ENUM('Head','Spouse','Son','Daughter','Other') DEFAULT 'Other',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_household_id (household_id),
    INDEX idx_full_name (full_name),
    INDEX idx_relation_to_head (relation_to_head),
    CONSTRAINT fk_residents_household 
        FOREIGN KEY (household_id) 
        REFERENCES households(household_id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Note: Make sure the households table exists before running this migration
-- You can run the households_restructure.sql migration first if needed
