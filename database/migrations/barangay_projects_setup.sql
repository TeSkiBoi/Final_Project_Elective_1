-- ============================================================================
-- BARANGAY PROJECTS MODULE - DATABASE SETUP
-- ============================================================================
-- Description: Creates tables for barangay projects management system
-- Date: 2024
-- ============================================================================

-- Drop tables if they exist
DROP TABLE IF EXISTS project_budgets;
DROP TABLE IF EXISTS barangay_projects;

-- Create barangay_projects table
CREATE TABLE barangay_projects (
    project_id INT AUTO_INCREMENT PRIMARY KEY,
    project_name VARCHAR(255) NOT NULL,
    project_description TEXT,
    project_status ENUM('Planning', 'Ongoing', 'Completed', 'On Hold', 'Cancelled') DEFAULT 'Planning',
    start_date DATE,
    end_date DATE,
    completion_date DATE,
    proponent VARCHAR(255) NOT NULL,
    beneficiaries TEXT,
    location VARCHAR(255),
    total_budget DECIMAL(15, 2) DEFAULT 0.00,
    budget_utilized DECIMAL(15, 2) DEFAULT 0.00,
    budget_remaining DECIMAL(15, 2) DEFAULT 0.00,
    funding_source VARCHAR(255),
    project_category VARCHAR(100),
    priority_level ENUM('High', 'Medium', 'Low') DEFAULT 'Medium',
    progress_percentage INT DEFAULT 0,
    remarks TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_project_status (project_status),
    INDEX idx_start_date (start_date),
    INDEX idx_priority_level (priority_level),
    INDEX idx_project_category (project_category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create project_budgets table for detailed budget breakdown
CREATE TABLE project_budgets (
    budget_id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    budget_item VARCHAR(255) NOT NULL,
    budget_description TEXT,
    allocated_amount DECIMAL(12, 2) NOT NULL,
    spent_amount DECIMAL(12, 2) DEFAULT 0.00,
    remaining_amount DECIMAL(12, 2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES barangay_projects(project_id) ON DELETE CASCADE,
    INDEX idx_project_id (project_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample projects
INSERT INTO barangay_projects (project_name, project_description, project_status, start_date, end_date, proponent, beneficiaries, location, total_budget, budget_utilized, budget_remaining, funding_source, project_category, priority_level, progress_percentage, remarks) VALUES
('Barangay Hall Renovation', 'Complete renovation of the barangay hall including roof repair, repainting, and office equipment upgrade', 'Ongoing', '2024-01-15', '2024-06-30', 'Barangay Council', 'All residents and barangay staff', 'Barangay Hall Compound', 500000.00, 250000.00, 250000.00, 'Local Development Fund', 'Infrastructure', 'High', 50, 'Project is on schedule. Roof repair completed.'),
('Community Health Program', 'Free medical consultation, medicines distribution, and health education seminars for senior citizens and children', 'Completed', '2024-02-01', '2024-02-28', 'Barangay Health Committee', 'Senior citizens and children', 'Barangay Multi-purpose Hall', 150000.00, 150000.00, 0.00, 'DOH Partnership Fund', 'Health', 'High', 100, 'Successfully served 500+ beneficiaries'),
('Street Lighting Installation', 'Installation of LED street lights in all major roads and alleys for improved safety and security', 'Planning', '2024-07-01', '2024-12-31', 'Barangay Development Committee', 'All residents', 'Barangay-wide', 800000.00, 0.00, 800000.00, 'City Government Allocation', 'Infrastructure', 'High', 0, 'Awaiting approval from city government'),
('Livelihood Training Program', 'Skills training for unemployed residents including baking, sewing, and basic carpentry workshops', 'Ongoing', '2024-03-15', '2024-05-30', 'Barangay Livelihood Committee', 'Unemployed residents aged 18-60', 'Barangay Training Center', 200000.00, 80000.00, 120000.00, 'TESDA Partnership', 'Livelihood', 'Medium', 40, '50 participants enrolled. Equipment procured.'),
('Drainage System Improvement', 'Construction and improvement of drainage system to prevent flooding during rainy season', 'Completed', '2023-11-01', '2024-01-31', 'Barangay Engineering Office', 'Residents in flood-prone areas', 'Purok 1, 2, and 3', 1200000.00, 1200000.00, 0.00, 'Calamity Fund', 'Infrastructure', 'High', 100, 'Successfully reduced flooding incidents by 80%'),
('Youth Sports Development', 'Basketball league, sports equipment distribution, and coaching clinics for youth', 'Ongoing', '2024-04-01', '2024-10-31', 'Barangay Youth Council', 'Youth aged 12-25', 'Barangay Covered Court', 100000.00, 35000.00, 65000.00, 'Barangay Sports Fund', 'Youth Development', 'Low', 35, 'League ongoing. 12 teams participating.'),
('Senior Citizens Support Program', 'Monthly grocery assistance and quarterly birthday celebration for senior citizens', 'Ongoing', '2024-01-01', '2024-12-31', 'Office of Senior Citizens Affairs', 'All registered senior citizens', 'Barangay Hall', 300000.00, 150000.00, 150000.00, 'Senior Citizens Fund', 'Social Services', 'Medium', 50, '250 senior citizens receiving monthly support'),
('Solid Waste Management Enhancement', 'Procurement of waste segregation bins and establishment of materials recovery facility', 'Planning', '2024-08-01', '2025-03-31', 'Barangay Environment Committee', 'All residents', 'Barangay-wide', 600000.00, 0.00, 600000.00, 'Environmental Fund', 'Environment', 'High', 0, 'Site selection ongoing for MRF location');

-- Insert sample budget breakdowns
INSERT INTO project_budgets (project_id, budget_item, budget_description, allocated_amount, spent_amount, remaining_amount) VALUES
(1, 'Construction Materials', 'Cement, steel bars, roofing materials, paint', 250000.00, 150000.00, 100000.00),
(1, 'Labor Cost', 'Mason, carpenter, painter wages', 150000.00, 80000.00, 70000.00),
(1, 'Office Equipment', 'Computers, printers, furniture', 100000.00, 20000.00, 80000.00),
(2, 'Medical Supplies', 'Medicines, vitamins, medical equipment', 80000.00, 80000.00, 0.00),
(2, 'Professional Fees', 'Doctors, nurses, medical staff', 50000.00, 50000.00, 0.00),
(2, 'Venue and Food', 'Hall rental, snacks for participants', 20000.00, 20000.00, 0.00),
(4, 'Training Materials', 'Tools, equipment, instructional materials', 100000.00, 50000.00, 50000.00),
(4, 'Trainer Fees', 'Professional trainers and facilitators', 60000.00, 20000.00, 40000.00),
(4, 'Participant Allowance', 'Daily meal and transportation allowance', 40000.00, 10000.00, 30000.00);

-- Create view for project summary
CREATE OR REPLACE VIEW project_summary AS
SELECT 
    project_status,
    COUNT(*) AS project_count,
    SUM(total_budget) AS total_budget_allocation,
    SUM(budget_utilized) AS total_spent,
    SUM(budget_remaining) AS total_remaining,
    AVG(progress_percentage) AS average_progress
FROM barangay_projects
GROUP BY project_status;

-- Create view for project statistics
CREATE OR REPLACE VIEW project_statistics AS
SELECT 
    (SELECT COUNT(*) FROM barangay_projects) AS total_projects,
    (SELECT COUNT(*) FROM barangay_projects WHERE project_status = 'Ongoing') AS ongoing_projects,
    (SELECT COUNT(*) FROM barangay_projects WHERE project_status = 'Completed') AS completed_projects,
    (SELECT COALESCE(SUM(total_budget), 0) FROM barangay_projects) AS total_budget,
    (SELECT COALESCE(SUM(budget_utilized), 0) FROM barangay_projects) AS total_utilized,
    (SELECT COALESCE(SUM(budget_remaining), 0) FROM barangay_projects) AS total_remaining;

-- Verify installation
SELECT 'Tables created successfully!' AS Status;
SELECT * FROM project_statistics;
SELECT * FROM project_summary;
SELECT COUNT(*) AS project_count FROM barangay_projects;

-- ============================================================================
-- INSTALLATION COMPLETE!
-- ============================================================================
