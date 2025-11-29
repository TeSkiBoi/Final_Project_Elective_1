/**
 * Migration: Update Residents Table Structure
 * Date: 2024
 * Description: Migrates residents table from INT id to VARCHAR(20) resident_id
 *              Updates field structure to use first_name, middle_name, last_name, age, contact_no, gmail
 *              Replaces old fields: full_name, birthdate, gender, occupation, relation_to_head
 */

-- Drop existing residents table (WARNING: This will delete all existing data)
DROP TABLE IF EXISTS residents;

-- Create new residents table with VARCHAR primary key and new schema
CREATE TABLE residents (
    resident_id VARCHAR(20) PRIMARY KEY,
    household_id VARCHAR(20) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    contact_no VARCHAR(20),
    gmail VARCHAR(150),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (household_id) REFERENCES households(household_id) ON DELETE CASCADE
);

-- Insert sample data
INSERT INTO residents (resident_id, household_id, first_name, middle_name, last_name, age, contact_no, gmail) VALUES
('R001', 'HH001', 'Juan', 'Dela', 'Cruz', 45, '09171234567', 'juan.cruz@gmail.com'),
('R002', 'HH001', 'Maria', 'Santos', 'Cruz', 42, '09187654321', 'maria.cruz@gmail.com'),
('R003', 'HH001', 'Pedro', NULL, 'Cruz', 18, '09191234567', 'pedro.cruz@gmail.com'),
('R004', 'HH002', 'Jose', 'Garcia', 'Reyes', 38, '09161234567', 'jose.reyes@gmail.com'),
('R005', 'HH002', 'Ana', 'Lopez', 'Reyes', 35, NULL, 'ana.reyes@gmail.com'),
('R006', 'HH003', 'Carlos', NULL, 'Santos', 50, '09181234567', NULL),
('R007', 'HH003', 'Elena', 'Martinez', 'Santos', 48, '09171234568', 'elena.santos@gmail.com'),
('R008', 'HH003', 'Miguel', NULL, 'Santos', 20, '09191234568', 'miguel.santos@gmail.com');

/**
 * MIGRATION NOTES:
 * 
 * 1. resident_id now uses VARCHAR(20) format: R001, R002, R003...
 * 2. household_id is now VARCHAR(20) matching households table
 * 3. Full name split into: first_name, middle_name (nullable), last_name
 * 4. Replaced birthdate + gender with age (INT)
 * 5. Added contact_no (nullable phone number)
 * 6. Added gmail (nullable email address)
 * 7. Removed: occupation, relation_to_head fields
 * 8. Foreign key constraint ensures household_id exists in households table
 * 9. ON DELETE CASCADE removes residents when household is deleted
 * 
 * AUTO-GENERATION:
 * The Model's generateNextId() method automatically generates next resident_id
 * No manual ID assignment needed in the application layer
 */
