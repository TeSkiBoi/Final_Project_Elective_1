<?php
/**
 * Resident Model
 * Handles database operations for residents
 */

require_once __DIR__ . '/../Config/Database.php';

class Resident {
    private $connection;
    private $table = 'residents';

    public function __construct() {
        $database = new Database();
        $this->connection = $database->connect();
    }

    public function getAll() {
        $query = "SELECT r.id, r.full_name, r.birthdate, r.gender, r.occupation, r.household_id, r.relation_to_head, h.household_no 
                  FROM " . $this->table . " r 
                  LEFT JOIN households h ON r.household_id = h.household_id 
                  ORDER BY r.full_name ASC";
        $result = $this->connection->query($query);

        if (!$result) return false;

        $rows = [];
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
        return $rows;
    }

    public function getById($id) {
        $query = "SELECT r.*, h.household_no FROM " . $this->table . " r LEFT JOIN households h ON r.household_id = h.household_id WHERE r.id = ? LIMIT 1";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) return null;
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res ? $res->fetch_assoc() : null;
    }



    public function create($data) {
        try {
            // Required fields validation
            $required = ['full_name', 'birthdate', 'gender', 'household_id'];
            foreach ($required as $f) {
                if (empty($data[$f])) {
                    return [
                        'success' => false,
                        'message' => ucfirst(str_replace('_', ' ', $f)) . ' is required',
                        'error_type' => 'validation'
                    ];
                }
            }

            $full_name = $data['full_name'];
            $birthdate = $data['birthdate'];
            $gender = $data['gender'];
            $occupation = $data['occupation'] ?? null;
            $household_id = $data['household_id'];
            $relation_to_head = $data['relation_to_head'] ?? 'Other';

            $query = "INSERT INTO " . $this->table . " (full_name, birthdate, gender, occupation, household_id, relation_to_head) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }

            $stmt->bind_param('ssss is', $full_name, $birthdate, $gender, $occupation, $household_id, $relation_to_head);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Resident created successfully!',
                    'id' => $this->connection->insert_id
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error creating resident: ' . $stmt->error,
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

    public function update($id, $data) {
        try {
            // Validate resident exists
            $existing = $this->getById($id);
            if (!$existing) {
                return [
                    'success' => false,
                    'message' => 'Resident not found',
                    'error_type' => 'not_found'
                ];
            }

            $full_name = $data['full_name'] ?? $existing['full_name'];
            $birthdate = $data['birthdate'] ?? $existing['birthdate'];
            $gender = $data['gender'] ?? $existing['gender'];
            $occupation = $data['occupation'] ?? $existing['occupation'];
            $household_id = $data['household_id'] ?? $existing['household_id'];
            $relation_to_head = $data['relation_to_head'] ?? $existing['relation_to_head'];

            $query = "UPDATE " . $this->table . " SET full_name = ?, birthdate = ?, gender = ?, occupation = ?, household_id = ?, relation_to_head = ? WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }

            $stmt->bind_param('ssssi si', $full_name, $birthdate, $gender, $occupation, $household_id, $relation_to_head, $id);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Resident updated successfully!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error updating resident: ' . $stmt->error,
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

    public function delete($id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }

            $stmt->bind_param('i', $id);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Resident deleted successfully!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error deleting resident: ' . $stmt->error,
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
