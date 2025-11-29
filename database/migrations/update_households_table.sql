-- Migration: Update households table structure
-- Date: November 28, 2025
-- Description: Migrate households table to new schema with VARCHAR household_id, family_no, and full_name

-- Step 1: Backup existing data (RECOMMENDED - uncomment if needed)
-- CREATE TABLE households_backup AS SELECT * FROM households;

-- Step 2: Drop the existing households table
DROP TABLE IF EXISTS households;

-- Step 3: Create new households table with updated structure
CREATE TABLE households (
    household_id VARCHAR(20) PRIMARY KEY,
    family_no INT NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    address VARCHAR(255) NOT NULL,
    income DECIMAL(12,2) DEFAULT 0.00
);

-- Step 4: Add indexes for faster queries
CREATE INDEX idx_family_no ON households(family_no);
CREATE INDEX idx_full_name ON households(full_name);

-- Step 5: Insert sample data (optional - comment out if not needed)
INSERT INTO households (household_id, family_no, full_name, address, income) VALUES
('HH001', 1, 'Juan Dela Cruz', '123 Main Street, Barangay Centro', 25000.00),
('HH002', 2, 'Maria Santos', '456 Oak Avenue, Barangay Poblacion', 30000.00),
('HH003', 3, 'Pedro Reyes', '789 Pine Road, Barangay San Isidro', 20000.00),
('HH004', 4, 'Ana Garcia', '321 Elm Street, Barangay Santo Niño', 18000.00),
('HH005', 5, 'Carlos Ramos', '654 Maple Drive, Barangay Bagong Silang', 22000.00);

-- Migration Complete
-- Note: This migration will delete all existing household data
-- Make sure to backup your data before running this migration if you want to preserve it
