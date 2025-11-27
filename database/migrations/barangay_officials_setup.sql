-- =====================================================
-- BARANGAY OFFICIALS ORG CHART - DATABASE SETUP
-- =====================================================
-- This script creates the necessary table and sample data
-- for the Barangay Officials Organizational Chart module
-- =====================================================

-- Drop table if exists (for fresh installation)
DROP TABLE IF EXISTS barangay_officials;

-- =====================================================
-- TABLE: barangay_officials
-- Stores information about barangay officials and their positions
-- =====================================================
CREATE TABLE barangay_officials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_title VARCHAR(100) NOT NULL,
    full_name VARCHAR(255),
    image_path VARCHAR(255),
    display_order INT NOT NULL DEFAULT 0,
    is_active ENUM('Yes', 'No') DEFAULT 'Yes',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_position_title (position_title),
    INDEX idx_display_order (display_order),
    INDEX idx_is_active (is_active),
    UNIQUE KEY unique_position (position_title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERT DEFAULT BARANGAY POSITIONS
-- These are the standard positions required in a barangay
-- Admin can update names and images through the system
-- =====================================================

INSERT INTO barangay_officials (position_title, full_name, image_path, display_order, is_active) VALUES
-- Top Level
('Barangay Chairman', 'Hon. Juan Dela Cruz', 'default_chairman.png', 1, 'Yes'),

-- Second Level - Executive Officers
('Barangay Secretary', 'Maria Santos', 'default_secretary.png', 2, 'Yes'),
('Barangay Treasurer', 'Pedro Reyes', 'default_treasurer.png', 3, 'Yes'),

-- Third Level - Barangay Kagawads (7 Councilors)
('Barangay Kagawad 1', 'Rosa Martinez', 'default_kagawad.png', 4, 'Yes'),
('Barangay Kagawad 2', 'Carlos Ramos', 'default_kagawad.png', 5, 'Yes'),
('Barangay Kagawad 3', 'Elena Fernandez', 'default_kagawad.png', 6, 'Yes'),
('Barangay Kagawad 4', 'Miguel Castro', 'default_kagawad.png', 7, 'Yes'),
('Barangay Kagawad 5', 'Teresa Aquino', 'default_kagawad.png', 8, 'Yes'),
('Barangay Kagawad 6', 'Antonio Villanueva', 'default_kagawad.png', 9, 'Yes'),
('Barangay Kagawad 7', 'Lorna Bautista', 'default_kagawad.png', 10, 'Yes'),

-- Fourth Level - Youth and Security
('SK Chairman', 'Jose Garcia Jr.', 'default_sk.png', 11, 'Yes'),
('Barangay Tanod', 'Roberto Cruz', 'default_tanod.png', 12, 'Yes');

-- =====================================================
-- NOTES:
-- 1. position_title is UNIQUE to prevent duplicate positions
-- 2. display_order determines the hierarchy and display sequence
-- 3. image_path should point to assets/uploads/officials/ folder
-- 4. is_active allows hiding positions without deleting
-- 5. Default placeholder images should be placed in assets/img/
-- =====================================================
