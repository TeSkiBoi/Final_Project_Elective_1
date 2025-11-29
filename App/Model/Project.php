<?php
/**
 * Barangay Project Model
 * Handles barangay projects data management
 */

require_once __DIR__ . '/../Config/Database.php';

class Project {
    private $db;
    private $connection;

    public function __construct() {
        $this->db = new Database();
        $this->connection = $this->db->connect();
    }

    /**
     * Get all projects
     */
    public function getAll() {
        $query = "SELECT * FROM barangay_projects ORDER BY created_at DESC";
        $result = $this->connection->query($query);
        
        $projects = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $projects[] = $row;
            }
        }
        return $projects;
    }

    /**
     * Get project by ID
     */
    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM barangay_projects WHERE project_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Create new project
     */
    public function create($data) {
        // budget_remaining is a generated column (total_budget - budget_utilized) and should not be set manually
        $stmt = $this->connection->prepare(
            "INSERT INTO barangay_projects 
            (project_name, project_description, project_status, start_date, end_date, proponent, 
             beneficiaries, location, total_budget, budget_utilized, 
             funding_source, project_category, priority_level, progress_percentage, remarks) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        
        $stmt->bind_param(
            "ssssssssddsssis",
            $data['project_name'],
            $data['project_description'],
            $data['project_status'],
            $data['start_date'],
            $data['end_date'],
            $data['proponent'],
            $data['beneficiaries'],
            $data['location'],
            $data['total_budget'],
            $data['budget_utilized'],
            $data['funding_source'],
            $data['project_category'],
            $data['priority_level'],
            $data['progress_percentage'],
            $data['remarks']
        );
        
        return $stmt->execute();
    }

    /**
     * Update project
     */
    public function update($id, $data) {
        $stmt = $this->connection->prepare(
            "UPDATE barangay_projects 
            SET project_name = ?, project_description = ?, project_status = ?, 
                start_date = ?, end_date = ?, proponent = ?, beneficiaries = ?, 
                location = ?, total_budget = ?, budget_utilized = ?,
                funding_source = ?, project_category = ?, priority_level = ?, 
                progress_percentage = ?, remarks = ?
            WHERE project_id = ?"
        );
        
        $stmt->bind_param(
            "ssssssssddsssisi",
            $data['project_name'],
            $data['project_description'],
            $data['project_status'],
            $data['start_date'],
            $data['end_date'],
            $data['proponent'],
            $data['beneficiaries'],
            $data['location'],
            $data['total_budget'],
            $data['budget_utilized'],
            $data['funding_source'],
            $data['project_category'],
            $data['priority_level'],
            $data['progress_percentage'],
            $data['remarks'],
            $id
        );
        
        return $stmt->execute();
    }

    /**
     * Delete project
     */
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM barangay_projects WHERE project_id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Get project statistics
     */
    public function getStatistics() {
        $query = "SELECT * FROM project_statistics";
        $result = $this->connection->query($query);
        return $result->fetch_assoc();
    }

    /**
     * Get projects by status
     */
    public function getByStatus($status) {
        $stmt = $this->connection->prepare(
            "SELECT * FROM barangay_projects WHERE project_status = ? ORDER BY start_date DESC"
        );
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $projects = [];
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
        return $projects;
    }

    /**
     * Get projects by category
     */
    public function getByCategory($category) {
        $stmt = $this->connection->prepare(
            "SELECT * FROM barangay_projects WHERE project_category = ? ORDER BY start_date DESC"
        );
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $projects = [];
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
        return $projects;
    }

    /**
     * Get all categories
     */
    public function getCategories() {
        $query = "SELECT DISTINCT project_category FROM barangay_projects WHERE project_category IS NOT NULL ORDER BY project_category";
        $result = $this->connection->query($query);
        
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['project_category'];
        }
        return $categories;
    }

    /**
     * Get all proponents
     */
    public function getProponents() {
        $query = "SELECT DISTINCT proponent FROM barangay_projects ORDER BY proponent";
        $result = $this->connection->query($query);
        
        $proponents = [];
        while ($row = $result->fetch_assoc()) {
            $proponents[] = $row['proponent'];
        }
        return $proponents;
    }
}
