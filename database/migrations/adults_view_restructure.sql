-- ============================================
-- Adults View - Database Migration
-- Creates view for adults (ages 18-59)
-- ============================================

USE student_information_system;

-- Drop existing view if it exists
DROP VIEW IF EXISTS adults_view;

-- Create Adults View (Ages 18-59)
CREATE VIEW adults_view AS
SELECT 
    r.id AS person_id,
    SUBSTRING_INDEX(r.full_name, ' ', 1) AS first_name,
    SUBSTRING_INDEX(SUBSTRING_INDEX(r.full_name, ' ', 2), ' ', -1) AS middle_name,
    SUBSTRING_INDEX(r.full_name, ' ', -1) AS last_name,
    r.birthdate,
    r.gender,
    FLOOR(DATEDIFF(CURDATE(), r.birthdate)/365) AS age,
    r.household_id,
    r.relation_to_head
FROM residents r
WHERE FLOOR(DATEDIFF(CURDATE(), r.birthdate)/365) BETWEEN 18 AND 59
ORDER BY r.full_name ASC;

-- Verify view creation
SELECT 
    'Adults View Created Successfully!' AS status,
    COUNT(*) AS total_adults
FROM adults_view;

-- Show sample data (first 5 adults)
SELECT * FROM adults_view LIMIT 5;
