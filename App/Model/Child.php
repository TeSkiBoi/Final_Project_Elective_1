<?php
/**
 * Child Model
 * Handles database operations for children
 */

require_once __DIR__ . '/../Config/Database.php';

class Child {
    private $connection;
    private $table = 'children';

    public function __construct() {
        $database = new Database();
        $this->connection = $database->connect();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY child_id ASC";
        $result = $this->connection->query($query);

        if (!$result) return false;

        $rows = [];
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
        return $rows;
    }

    public function getById($child_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE child_id = ? LIMIT 1";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) return null;
        $stmt->bind_param('s', $child_id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res ? $res->fetch_assoc() : null;
    }

    private function generateChildId() {
        $query = "SELECT child_id FROM " . $this->table . " WHERE child_id LIKE 'C%' ORDER BY child_id DESC LIMIT 1";
        $result = $this->connection->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastId = $row['child_id'];
            $number = (int)substr($lastId, 1) + 1;
        } else {
            $number = 1;
        }

        return 'C' . str_pad($number, 9, '0', STR_PAD_LEFT);
    }

    public function create($data) {
        try {
            // Required fields validation
            $required = ['firstname', 'lastname', 'birthdate', 'gender', 'age'];
            foreach ($required as $f) {
                if (empty($data[$f])) {
                    return [
                        'success' => false,
                        'message' => ucfirst($f) . ' is required',
                        'error_type' => 'validation'
                    ];
                }
            }

            $child_id = !empty($data['child_id']) ? $data['child_id'] : $this->generateChildId();

            // Check for duplicate child_id
            $check = $this->getById($child_id);
            if ($check) {
                return [
                    'success' => false,
                    'message' => 'Child ID already exists',
                    'error_type' => 'duplicate_id'
                ];
            }

            $firstname = $data['firstname'];
            $middlename = $data['middlename'] ?? null;
            $lastname = $data['lastname'];
            $birthdate = $data['birthdate'];
            $gender = $data['gender'];
            $age = (int)$data['age'];

            $query = "INSERT INTO " . $this->table . " (child_id, firstname, middlename, lastname, birthdate, gender, age) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }

            $stmt->bind_param('ssssssi', $child_id, $firstname, $middlename, $lastname, $birthdate, $gender, $age);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Child created successfully!',
                    'child_id' => $child_id
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error creating child: ' . $stmt->error,
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

    public function update($child_id, $data) {
        try {
            // Validate child exists
            $existing = $this->getById($child_id);
            if (!$existing) {
                return [
                    'success' => false,
                    'message' => 'Child not found',
                    'error_type' => 'not_found'
                ];
            }

            $firstname = $data['firstname'] ?? $existing['firstname'];
            $middlename = $data['middlename'] ?? $existing['middlename'];
            $lastname = $data['lastname'] ?? $existing['lastname'];
            $birthdate = $data['birthdate'] ?? $existing['birthdate'];
            $gender = $data['gender'] ?? $existing['gender'];
            $age = isset($data['age']) ? (int)$data['age'] : (int)$existing['age'];

            $query = "UPDATE " . $this->table . " SET firstname = ?, middlename = ?, lastname = ?, birthdate = ?, gender = ?, age = ? WHERE child_id = ?";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }

            $stmt->bind_param('sssssis', $firstname, $middlename, $lastname, $birthdate, $gender, $age, $child_id);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Child updated successfully!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error updating child: ' . $stmt->error,
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

    public function delete($child_id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE child_id = ?";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }

            $stmt->bind_param('s', $child_id);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Child deleted successfully!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error deleting child: ' . $stmt->error,
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
