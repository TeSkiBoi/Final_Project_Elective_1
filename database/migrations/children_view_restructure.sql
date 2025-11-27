-- ============================================================================
-- CHILDREN VIEW RESTRUCTURE
-- ============================================================================
-- Description: Creates SQL view for children (ages 0-17) with parsed name fields
-- Date: 2024
-- Author: System
-- ============================================================================

-- Drop view if it exists
DROP VIEW IF EXISTS children_view;

-- Create children_view with parsed name fields
CREATE OR REPLACE VIEW children_view AS
SELECT 
    r.id AS person_id,
    SUBSTRING_INDEX(r.full_name, ' ', 1) AS first_name,
    SUBSTRING_INDEX(SUBSTRING_INDEX(r.full_name, ' ', 2), ' ', -1) AS middle_name,
    SUBSTRING_INDEX(r.full_name, ' ', -1) AS last_name,
    r.birthdate,
    r.gender,
    FLOOR(DATEDIFF(CURDATE(), r.birthdate)/365) AS age,
    r.household_id,
    h.household_no,
    h.address,
    r.relation_to_head
FROM 
    residents r
LEFT JOIN 
    households h ON r.household_id = h.household_id
WHERE 
    FLOOR(DATEDIFF(CURDATE(), r.birthdate)/365) <= 17
ORDER BY 
    r.full_name ASC;

-- ============================================================================
-- VERIFICATION
-- ============================================================================
-- Test the view
SELECT * FROM children_view LIMIT 5;

-- Count records
SELECT COUNT(*) AS children_count FROM children_view;

-- ============================================================================
-- NOTES
-- ============================================================================
-- 1. View filters residents who are 17 years or younger
-- 2. Name parsing uses SUBSTRING_INDEX to split full_name into components
-- 3. Age is calculated dynamically from birthdate
-- 4. Includes household information via LEFT JOIN
-- 5. Results ordered alphabetically by full name
-- 6. Occupation field excluded (not typically relevant for children)
-- 
-- USAGE:
-- - Used by children.php view to display children
-- - Provides consistent data structure across all age-based views
-- - Automatically updates as birthdate changes (dynamic age calculation)
-- ============================================================================
