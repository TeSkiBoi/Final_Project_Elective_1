<?php
/**
 * Household Model
 * Handles database operations for households
 * 
 * SQL Structure:
 * CREATE TABLE households (
 *   household_id VARCHAR(20) PRIMARY KEY,
 *   family_no INT NOT NULL,
 *   full_name VARCHAR(150) NOT NULL,
 *   address VARCHAR(255) NOT NULL,
 *   income DECIMAL(12,2) DEFAULT 0.00
 * );
 */

require_once __DIR__ . '/../Config/Database.php';

class Household {
    private $connection;
    private $table = 'households';

    public function __construct() {
        $database = new Database();
        $this->connection = $database->connect();
    }

    /**
     * Get all households
     */
    public function getAll() {
        $query = "SELECT household_id, family_no, full_name, address, income FROM " . $this->table . " ORDER BY family_no ASC";
        $result = $this->connection->query($query);

        if (!$result) {
            return false;
        }

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Get household by ID
     */
    public function getById($household_id) {
        $query = "SELECT household_id, family_no, full_name, address, income FROM " . $this->table . " WHERE household_id = ? LIMIT 1";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) return null;
        $stmt->bind_param('s', $household_id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    /**
     * Create household
     */
    public function create($household_id, $family_no, $full_name, $address, $income = 0.00) {
        try {
            // Validate required fields
            if (empty($household_id) || empty($family_no) || empty($full_name) || empty($address)) {
                return [
                    'success' => false,
                    'message' => 'Household ID, Family No, Full Name, and Address are required',
                    'error_type' => 'validation'
                ];
            }

            // Check if household_id already exists
            $checkQuery = "SELECT household_id FROM " . $this->table . " WHERE household_id = ? LIMIT 1";
            $checkStmt = $this->connection->prepare($checkQuery);
            $checkStmt->bind_param('s', $household_id);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            
            if ($checkResult->num_rows > 0) {
                return [
                    'success' => false,
                    'message' => 'Household ID already exists',
                    'error_type' => 'validation'
                ];
            }

            // Bind parameters: s=string, i=integer, s=string, s=string, d=double
            // household_id(s), family_no(i), full_name(s), address(s), income(d)
            $query = "INSERT INTO " . $this->table . " (household_id, family_no, full_name, address, income) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }
            
            $stmt->bind_param('sissd', $household_id, $family_no, $full_name, $address, $income);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Household created successfully!',
                    'household_id' => $household_id
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error creating household: ' . $stmt->error,
                    'error_type' => 'database'
                ];
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception occurred: ' . $e->getMessage(),
                'error_type' => 'exception'
            ];
        }
    }

    /**
     * Update household
     */
    public function update($household_id, $family_no, $full_name, $address, $income = 0.00) {
        try {
            // Validate household exists
            $existing = $this->getById($household_id);
            if (!$existing) {
                return [
                    'success' => false,
                    'message' => 'Household not found',
                    'error_type' => 'not_found'
                ];
            }

            // Validate required fields
            if (empty($family_no) || empty($full_name) || empty($address)) {
                return [
                    'success' => false,
                    'message' => 'Family No, Full Name, and Address are required',
                    'error_type' => 'validation'
                ];
            }

            // Bind parameters: i=integer, s=string, s=string, d=double, s=string
            // family_no(i), full_name(s), address(s), income(d), household_id(s)
            $query = "UPDATE " . $this->table . " SET family_no = ?, full_name = ?, address = ?, income = ? WHERE household_id = ?";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }
            
            $stmt->bind_param('issds', $family_no, $full_name, $address, $income, $household_id);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Household updated successfully!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error updating household: ' . $stmt->error,
                    'error_type' => 'database'
                ];
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception occurred: ' . $e->getMessage(),
                'error_type' => 'exception'
            ];
        }
    }

    /**
     * Delete household
     */
    public function delete($household_id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE household_id = ?";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }
            
            $stmt->bind_param('s', $household_id);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Household deleted successfully!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error deleting household: ' . $stmt->error,
                    'error_type' => 'database'
                ];
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception occurred: ' . $e->getMessage(),
                'error_type' => 'exception'
            ];
        }
    }
}
?>
