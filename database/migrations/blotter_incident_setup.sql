-- =====================================================
-- BLOTTER AND INCIDENT RECORDING MODULE - DATABASE SETUP
-- =====================================================
-- This script creates the necessary tables and sample data
-- for the Blotter and Incident Recording management module
-- =====================================================

-- Drop tables if they exist (for fresh installation)
DROP TABLE IF EXISTS incident_resolutions;
DROP TABLE IF EXISTS blotter_incidents;
DROP VIEW IF EXISTS incident_summary;
DROP VIEW IF EXISTS incident_statistics;

-- =====================================================
-- TABLE: blotter_incidents
-- Stores information about all barangay incidents and complaints
-- =====================================================
CREATE TABLE blotter_incidents (
    incident_id INT AUTO_INCREMENT PRIMARY KEY,
    case_number VARCHAR(50) UNIQUE NOT NULL,
    incident_type ENUM('Complaint', 'Dispute', 'Noise Complaint', 'Domestic Issue', 'Theft', 'Assault', 'Vandalism', 'Public Disturbance', 'Other') NOT NULL DEFAULT 'Complaint',
    incident_date DATE NOT NULL,
    incident_time TIME,
    incident_location TEXT NOT NULL,
    complainant_name VARCHAR(255) NOT NULL,
    complainant_address TEXT,
    complainant_contact VARCHAR(50),
    respondent_name VARCHAR(255) NOT NULL,
    respondent_address TEXT,
    respondent_contact VARCHAR(50),
    incident_description TEXT NOT NULL,
    witnesses TEXT,
    incident_status ENUM('Pending', 'Under Investigation', 'For Mediation', 'Resolved', 'Closed', 'Escalated') NOT NULL DEFAULT 'Pending',
    priority_level ENUM('High', 'Medium', 'Low') DEFAULT 'Medium',
    assigned_to VARCHAR(255),
    filed_by VARCHAR(255),
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_case_number (case_number),
    INDEX idx_incident_date (incident_date),
    INDEX idx_status (incident_status),
    INDEX idx_type (incident_type),
    INDEX idx_priority (priority_level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: incident_resolutions
-- Stores resolution details for each incident
-- =====================================================
CREATE TABLE incident_resolutions (
    resolution_id INT AUTO_INCREMENT PRIMARY KEY,
    incident_id INT NOT NULL,
    resolution_date DATE NOT NULL,
    resolution_type ENUM('Mediation', 'Settlement', 'Referral', 'Dismissed', 'Other') NOT NULL,
    resolution_details TEXT NOT NULL,
    resolved_by VARCHAR(255),
    settlement_amount DECIMAL(15, 2) DEFAULT 0.00,
    agreement_terms TEXT,
    follow_up_required ENUM('Yes', 'No') DEFAULT 'No',
    follow_up_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (incident_id) REFERENCES blotter_incidents(incident_id) ON DELETE CASCADE,
    INDEX idx_incident_id (incident_id),
    INDEX idx_resolution_date (resolution_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERT SAMPLE DATA
-- =====================================================

-- Sample Blotter Incidents
INSERT INTO blotter_incidents (
    case_number, incident_type, incident_date, incident_time, incident_location,
    complainant_name, complainant_address, complainant_contact,
    respondent_name, respondent_address, respondent_contact,
    incident_description, witnesses, incident_status, priority_level,
    assigned_to, filed_by, remarks
) VALUES
(
    'BLT-2024-001',
    'Noise Complaint',
    '2024-11-20',
    '23:30:00',
    'Purok 3, Main Street',
    'Juan Dela Cruz',
    'Block 5 Lot 12, Purok 3',
    '0912-345-6789',
    'Pedro Santos',
    'Block 5 Lot 15, Purok 3',
    '0923-456-7890',
    'Loud music and karaoke late at night disturbing the neighborhood. Complainant reported that this has been ongoing for several nights.',
    'Maria Garcia (neighbor), Jose Reyes (neighbor)',
    'Resolved',
    'Low',
    'Barangay Tanod - Roberto Cruz',
    'Desk Officer - Ana Lopez',
    'Issue resolved through mediation. Respondent agreed to lower volume and observe quiet hours.'
),
(
    'BLT-2024-002',
    'Dispute',
    '2024-11-22',
    '14:00:00',
    'Purok 1, Property Line between Lot 8 and Lot 9',
    'Rosa Martinez',
    'Block 2 Lot 8, Purok 1',
    '0918-765-4321',
    'Carlos Ramos',
    'Block 2 Lot 9, Purok 1',
    '0917-654-3210',
    'Property boundary dispute. Complainant claims respondent built a fence encroaching on her property. Both parties present conflicting survey documents.',
    'Surveyor - Engr. Manuel Torres',
    'Under Investigation',
    'High',
    'Barangay Chairman - Antonio Mendoza',
    'Secretary - Linda Cruz',
    'Scheduled for mediation on December 1, 2024. Both parties to bring original land titles and survey plans.'
),
(
    'BLT-2024-003',
    'Theft',
    '2024-11-23',
    '03:00:00',
    'Purok 2, Residential Area',
    'Elena Fernandez',
    'Block 3 Lot 20, Purok 2',
    '0915-234-5678',
    'Unknown Suspect',
    'Unknown',
    'N/A',
    'Complainant reported theft of motorcycle (Plate No. ABC-1234) parked in front of house. CCTV footage shows two unidentified individuals at 3:00 AM.',
    'CCTV footage available',
    'Escalated',
    'High',
    'Barangay Police - Sgt. Ramon Diaz',
    'Desk Officer - Ana Lopez',
    'Case escalated to municipal police station. Police report filed. Investigation ongoing.'
),
(
    'BLT-2024-004',
    'Domestic Issue',
    '2024-11-24',
    '20:15:00',
    'Purok 4, Inside Residence',
    'Anonymous Neighbor',
    'Withheld for safety',
    'Withheld',
    'Miguel Castro',
    'Block 6 Lot 30, Purok 4',
    '0920-111-2222',
    'Loud argument and shouting heard from residence. Concerned neighbor reported possible domestic violence. Children crying heard.',
    'Anonymous caller, Barangay Tanod who responded',
    'For Mediation',
    'High',
    'VAWC Desk Officer - Dr. Sofia Reyes',
    'Emergency Hotline',
    'VAWC protocol activated. Social worker and counselor assigned. Family temporarily separated for cooling period.'
),
(
    'BLT-2024-005',
    'Public Disturbance',
    '2024-11-25',
    '18:00:00',
    'Barangay Basketball Court',
    'Multiple Residents',
    'Purok 5 Residents',
    'N/A',
    'Group of Teenagers',
    'Various addresses in Purok 5',
    'N/A',
    'Group of teenagers drinking alcohol and causing disturbance at basketball court. Refusing to leave public area. Using profane language.',
    'Multiple residents, Barangay Tanod',
    'Resolved',
    'Medium',
    'Barangay Tanod - Roberto Cruz',
    'Resident - Mario Santos',
    'Teenagers were escorted home. Parents were called and advised. Liquor bottles confiscated.'
),
(
    'BLT-2024-006',
    'Complaint',
    '2024-11-25',
    '09:00:00',
    'Purok 3, Drainage Area',
    'Community Association',
    'Purok 3 Residents',
    '0919-888-7777',
    'Construction Company XYZ',
    'Construction Site, Purok 3',
    '0928-999-8888',
    'Ongoing construction blocking drainage system causing flooding in nearby houses. Debris and materials obstructing water flow.',
    'Photos and video documentation, Multiple affected residents',
    'Pending',
    'High',
    'Barangay Engineering - Engr. Pablo Cruz',
    'Kagawad - Teresa Aquino',
    'Notice to comply issued to construction company. Site inspection scheduled for November 28, 2024.'
),
(
    'BLT-2024-007',
    'Assault',
    '2024-11-24',
    '16:30:00',
    'Purok 2, Sari-sari Store',
    'Antonio Villanueva',
    'Block 4 Lot 25, Purok 2',
    '0916-333-4444',
    'Ricardo Bautista',
    'Block 4 Lot 28, Purok 2',
    '0917-555-6666',
    'Physical altercation over unpaid debt. Complainant sustained minor injuries (bruises on face and arms). Respondent admits to pushing complainant.',
    'Store owner - Lorna Santos, Customer - Felix Gomez',
    'For Mediation',
    'High',
    'Barangay Chairman - Antonio Mendoza',
    'Barangay Tanod - Roberto Cruz',
    'Medical certificate issued by barangay health center. Both parties agree to mediation on November 27, 2024.'
),
(
    'BLT-2024-008',
    'Vandalism',
    '2024-11-23',
    '02:00:00',
    'Barangay Hall Wall',
    'Barangay Hall Staff',
    'Barangay Hall',
    '555-1234',
    'Unknown Vandals',
    'Unknown',
    'N/A',
    'Graffiti spray-painted on barangay hall exterior wall. Offensive and inappropriate content. Estimated damage and repainting cost: 5,000 pesos.',
    'Security guard, CCTV footage (unclear)',
    'Under Investigation',
    'Medium',
    'Barangay Tanod - Roberto Cruz',
    'Security Guard - Domingo Reyes',
    'Photos taken for documentation. Reviewing CCTV footage. Asking nearby residents for information.'
);

-- Sample Resolution for Resolved Incident (BLT-2024-001)
INSERT INTO incident_resolutions (
    incident_id, resolution_date, resolution_type, resolution_details,
    resolved_by, settlement_amount, agreement_terms, follow_up_required
) VALUES
(
    1,
    '2024-11-21',
    'Mediation',
    'Both parties attended mediation session. Respondent acknowledged the noise complaint and agreed to be more considerate of neighbors. Complainant accepted apology.',
    'Lupong Tagapamayapa - Kagawad Maria Santos',
    0.00,
    'Respondent agrees to: 1) Stop karaoke after 9:00 PM on weekdays and 10:00 PM on weekends. 2) Keep music volume at reasonable levels. 3) Inform neighbors in advance of any special occasions. Complainant agrees to inform respondent first before filing future complaints.',
    'No'
);

-- Sample Resolution for Resolved Incident (BLT-2024-005)
INSERT INTO incident_resolutions (
    incident_id, resolution_date, resolution_type, resolution_details,
    resolved_by, settlement_amount, agreement_terms, follow_up_required
) VALUES
(
    5,
    '2024-11-25',
    'Other',
    'Parents of all teenagers involved were contacted and came to barangay hall. Counseling session conducted. Teenagers issued warning.',
    'Barangay Tanod - Roberto Cruz with SK Chairman',
    0.00,
    'Teenagers agree to: 1) Not drink alcohol in public places. 2) Respect community rules. 3) Attend SK-organized community service for 3 weekends. Parents agree to monitor children more closely.',
    'Yes'
);

-- =====================================================
-- CREATE VIEWS FOR REPORTING
-- =====================================================

-- View: Incident Summary by Status
CREATE VIEW incident_summary AS
SELECT 
    incident_status,
    COUNT(*) as incident_count,
    SUM(CASE WHEN priority_level = 'High' THEN 1 ELSE 0 END) as high_priority_count,
    SUM(CASE WHEN priority_level = 'Medium' THEN 1 ELSE 0 END) as medium_priority_count,
    SUM(CASE WHEN priority_level = 'Low' THEN 1 ELSE 0 END) as low_priority_count
FROM blotter_incidents
GROUP BY incident_status;

-- View: Overall Incident Statistics
CREATE VIEW incident_statistics AS
SELECT 
    COUNT(*) as total_incidents,
    SUM(CASE WHEN incident_status = 'Pending' THEN 1 ELSE 0 END) as pending_incidents,
    SUM(CASE WHEN incident_status = 'Under Investigation' THEN 1 ELSE 0 END) as investigating_incidents,
    SUM(CASE WHEN incident_status = 'For Mediation' THEN 1 ELSE 0 END) as mediation_incidents,
    SUM(CASE WHEN incident_status = 'Resolved' THEN 1 ELSE 0 END) as resolved_incidents,
    SUM(CASE WHEN incident_status = 'Closed' THEN 1 ELSE 0 END) as closed_incidents,
    SUM(CASE WHEN incident_status = 'Escalated' THEN 1 ELSE 0 END) as escalated_incidents,
    SUM(CASE WHEN priority_level = 'High' THEN 1 ELSE 0 END) as high_priority_total,
    SUM(CASE WHEN incident_type = 'Complaint' THEN 1 ELSE 0 END) as complaint_count,
    SUM(CASE WHEN incident_type = 'Dispute' THEN 1 ELSE 0 END) as dispute_count,
    SUM(CASE WHEN incident_type = 'Theft' THEN 1 ELSE 0 END) as theft_count,
    SUM(CASE WHEN incident_type = 'Assault' THEN 1 ELSE 0 END) as assault_count
FROM blotter_incidents;

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================

-- Verify tables were created
SELECT 'Tables created successfully' as status;

-- Show all incidents
SELECT incident_id, case_number, incident_type, incident_status, complainant_name, respondent_name, incident_date 
FROM blotter_incidents
ORDER BY incident_date DESC;

-- Show incident statistics
SELECT * FROM incident_statistics;

-- Show incident summary by status
SELECT * FROM incident_summary;

-- =====================================================
-- INSTALLATION COMPLETE
-- =====================================================
