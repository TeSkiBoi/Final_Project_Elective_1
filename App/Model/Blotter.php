<?php
/**
 * Blotter Model
 * Handles database operations for blotter incidents
 */

require_once __DIR__ . '/../Config/Database.php';

class Blotter {
    private $connection;

    public function __construct() {
        $database = new Database();
        $this->connection = $database->connect();
    }

    /**
     * Get all incidents
     */
    public function getAll() {
        $query = "SELECT * FROM blotter_incidents ORDER BY created_at DESC, incident_date DESC";
        $result = $this->connection->query($query);
        
        $incidents = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $incidents[] = $row;
            }
        }
        return $incidents;
    }

    /**
     * Get incident by ID
     */
    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM blotter_incidents WHERE incident_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Create new incident
     */
    public function create($data) {
        $stmt = $this->connection->prepare(
            "INSERT INTO blotter_incidents (
                case_number, incident_type, incident_date, incident_time, incident_location,
                complainant_name, complainant_address, complainant_contact,
                respondent_name, respondent_address, respondent_contact,
                incident_description, witnesses, incident_status, priority_level,
                assigned_to, filed_by, remarks
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssssssssssssssssss",
            $data['case_number'],
            $data['incident_type'],
            $data['incident_date'],
            $data['incident_time'],
            $data['incident_location'],
            $data['complainant_name'],
            $data['complainant_address'],
            $data['complainant_contact'],
            $data['respondent_name'],
            $data['respondent_address'],
            $data['respondent_contact'],
            $data['incident_description'],
            $data['witnesses'],
            $data['incident_status'],
            $data['priority_level'],
            $data['assigned_to'],
            $data['filed_by'],
            $data['remarks']
        );

        return $stmt->execute();
    }

    /**
     * Update incident
     */
    public function update($id, $data) {
        $stmt = $this->connection->prepare(
            "UPDATE blotter_incidents SET
                case_number = ?,
                incident_type = ?,
                incident_date = ?,
                incident_time = ?,
                incident_location = ?,
                complainant_name = ?,
                complainant_address = ?,
                complainant_contact = ?,
                respondent_name = ?,
                respondent_address = ?,
                respondent_contact = ?,
                incident_description = ?,
                witnesses = ?,
                incident_status = ?,
                priority_level = ?,
                assigned_to = ?,
                filed_by = ?,
                remarks = ?
            WHERE incident_id = ?"
        );

        $stmt->bind_param(
            "ssssssssssssssssssi",
            $data['case_number'],
            $data['incident_type'],
            $data['incident_date'],
            $data['incident_time'],
            $data['incident_location'],
            $data['complainant_name'],
            $data['complainant_address'],
            $data['complainant_contact'],
            $data['respondent_name'],
            $data['respondent_address'],
            $data['respondent_contact'],
            $data['incident_description'],
            $data['witnesses'],
            $data['incident_status'],
            $data['priority_level'],
            $data['assigned_to'],
            $data['filed_by'],
            $data['remarks'],
            $id
        );

        return $stmt->execute();
    }

    /**
     * Delete incident
     */
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM blotter_incidents WHERE incident_id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $query = "SELECT * FROM incident_statistics";
        $result = $this->connection->query($query);
        return $result->fetch_assoc();
    }

    /**
     * Get incidents by status
     */
    public function getByStatus($status) {
        $stmt = $this->connection->prepare(
            "SELECT * FROM blotter_incidents WHERE incident_status = ? ORDER BY incident_date DESC"
        );
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $incidents = [];
        while ($row = $result->fetch_assoc()) {
            $incidents[] = $row;
        }
        return $incidents;
    }

    /**
     * Get incidents by type
     */
    public function getByType($type) {
        $stmt = $this->connection->prepare(
            "SELECT * FROM blotter_incidents WHERE incident_type = ? ORDER BY incident_date DESC"
        );
        $stmt->bind_param("s", $type);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $incidents = [];
        while ($row = $result->fetch_assoc()) {
            $incidents[] = $row;
        }
        return $incidents;
    }

    /**
     * Generate unique case number
     */
    public function generateCaseNumber() {
        $year = date('Y');
        $query = "SELECT COUNT(*) as count FROM blotter_incidents WHERE YEAR(created_at) = $year";
        $result = $this->connection->query($query);
        $row = $result->fetch_assoc();
        $count = $row['count'] + 1;
        return 'BLT-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Check if case number exists
     */
    public function caseNumberExists($caseNumber, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->connection->prepare(
                "SELECT COUNT(*) as count FROM blotter_incidents WHERE case_number = ? AND incident_id != ?"
            );
            $stmt->bind_param("si", $caseNumber, $excludeId);
        } else {
            $stmt = $this->connection->prepare(
                "SELECT COUNT(*) as count FROM blotter_incidents WHERE case_number = ?"
            );
            $stmt->bind_param("s", $caseNumber);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }
}
