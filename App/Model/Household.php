<?php
/**
 * Household Model
 * Handles database operations for households
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
        $query = "SELECT household_id, head_resident_id, household_no, address, income, purok FROM " . $this->table . " ORDER BY household_no ASC";
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
        $query = "SELECT household_id, head_resident_id, household_no, address, income, purok FROM " . $this->table . " WHERE household_id = ? LIMIT 1";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) return null;
        $stmt->bind_param('i', $household_id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }



    /**
     * Create household
     */
    public function create($household_no, $address, $income, $purok, $head_resident_id = null) {
        try {
            // Validate required fields
            if (empty($household_no) || empty($address)) {
                return [
                    'success' => false,
                    'message' => 'Household No and Address are required',
                    'error_type' => 'validation'
                ];
            }

            // Check if household_no already exists
            $checkQuery = "SELECT household_id FROM " . $this->table . " WHERE household_no = ? LIMIT 1";
            $checkStmt = $this->connection->prepare($checkQuery);
            $checkStmt->bind_param('s', $household_no);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            
            if ($checkResult->num_rows > 0) {
                return [
                    'success' => false,
                    'message' => 'Household No already exists',
                    'error_type' => 'validation'
                ];
            }

            $query = "INSERT INTO " . $this->table . " (head_resident_id, household_no, address, income, purok) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }
            
            $stmt->bind_param('issds', $head_resident_id, $household_no, $address, $income, $purok);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Household created successfully!',
                    'household_id' => $this->connection->insert_id
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
    public function update($household_id, $household_no, $address, $income, $purok, $head_resident_id = null) {
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

            // Check if household_no already exists for other records
            $checkQuery = "SELECT household_id FROM " . $this->table . " WHERE household_no = ? AND household_id != ? LIMIT 1";
            $checkStmt = $this->connection->prepare($checkQuery);
            $checkStmt->bind_param('si', $household_no, $household_id);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            
            if ($checkResult->num_rows > 0) {
                return [
                    'success' => false,
                    'message' => 'Household No already exists',
                    'error_type' => 'validation'
                ];
            }

            $query = "UPDATE " . $this->table . " SET head_resident_id = ?, household_no = ?, address = ?, income = ?, purok = ? WHERE household_id = ?";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }
            
            $stmt->bind_param('issdsi', $head_resident_id, $household_no, $address, $income, $purok, $household_id);
            
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
            
            $stmt->bind_param('i', $household_id);
            
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
