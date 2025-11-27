<?php
/**
 * Adult Model
 * Handles database operations for adults
 */

require_once __DIR__ . '/../Config/Database.php';

class Adult {
    private $connection;
    private $table = 'adults';

    public function __construct() {
        $database = new Database();
        $this->connection = $database->connect();
    }

    /**
     * Get all adults
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY adult_id ASC";
        $result = $this->connection->query($query);

        if (!$result) return false;

        $rows = [];
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
        return $rows;
    }

    /**
     * Get adult by ID
     */
    public function getById($adult_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE adult_id = ? LIMIT 1";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) return null;
        $stmt->bind_param('s', $adult_id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res ? $res->fetch_assoc() : null;
    }

    /**
     * Generate unique adult ID
     */
    private function generateAdultId() {
        $query = "SELECT adult_id FROM " . $this->table . " WHERE adult_id LIKE 'A%' ORDER BY adult_id DESC LIMIT 1";
        $result = $this->connection->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastId = $row['adult_id'];
            $number = (int)substr($lastId, 1) + 1;
        } else {
            $number = 1;
        }

        return 'A' . str_pad($number, 9, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new adult
     */
    public function create($data) {
        try {
            // Required fields validation
            $required = ['firstname', 'lastname', 'birthdate', 'gender', 'age', 'status'];
            foreach ($required as $f) {
                if (empty($data[$f])) {
                    return [
                        'success' => false,
                        'message' => ucfirst($f) . ' is required',
                        'error_type' => 'validation'
                    ];
                }
            }

            // Validate age is at least 18
            if ((int)$data['age'] < 18) {
                return [
                    'success' => false,
                    'message' => 'Adults must be 18 years or older',
                    'error_type' => 'validation'
                ];
            }

            // Validate gender enum
            $validGenders = ['Male', 'Female', 'Other'];
            if (!in_array($data['gender'], $validGenders)) {
                return [
                    'success' => false,
                    'message' => 'Invalid gender value',
                    'error_type' => 'validation'
                ];
            }

            // Validate status enum (marital status)
            $validStatuses = ['Single', 'Married', 'Widowed', 'Live-in'];
            if (!in_array($data['status'], $validStatuses)) {
                return [
                    'success' => false,
                    'message' => 'Invalid marital status value',
                    'error_type' => 'validation'
                ];
            }

            $adult_id = !empty($data['adult_id']) ? $data['adult_id'] : $this->generateAdultId();

            // Check for duplicate adult_id
            $check = $this->getById($adult_id);
            if ($check) {
                return [
                    'success' => false,
                    'message' => 'Adult ID already exists',
                    'error_type' => 'duplicate_id'
                ];
            }

            $firstname = $data['firstname'];
            $middlename = $data['middlename'] ?? null;
            $lastname = $data['lastname'];
            $birthdate = $data['birthdate'];
            $gender = $data['gender'];
            $age = (int)$data['age'];
            $status = $data['status'];

            $query = "INSERT INTO " . $this->table . " (adult_id, firstname, middlename, lastname, birthdate, gender, age, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }

            $stmt->bind_param('ssssssis', $adult_id, $firstname, $middlename, $lastname, $birthdate, $gender, $age, $status);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Adult created successfully!',
                    'adult_id' => $adult_id
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error creating adult: ' . $stmt->error,
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
     * Update adult
     */
    public function update($adult_id, $data) {
        try {
            // Validate adult exists
            $existing = $this->getById($adult_id);
            if (!$existing) {
                return [
                    'success' => false,
                    'message' => 'Adult not found',
                    'error_type' => 'not_found'
                ];
            }

            // Validate age if provided
            if (isset($data['age']) && (int)$data['age'] < 18) {
                return [
                    'success' => false,
                    'message' => 'Adults must be 18 years or older',
                    'error_type' => 'validation'
                ];
            }

            // Validate gender if provided
            if (isset($data['gender'])) {
                $validGenders = ['Male', 'Female', 'Other'];
                if (!in_array($data['gender'], $validGenders)) {
                    return [
                        'success' => false,
                        'message' => 'Invalid gender value',
                        'error_type' => 'validation'
                    ];
                }
            }

            // Validate status if provided
            if (isset($data['status'])) {
                $validStatuses = ['Single', 'Married', 'Widowed', 'Live-in'];
                if (!in_array($data['status'], $validStatuses)) {
                    return [
                        'success' => false,
                        'message' => 'Invalid marital status value',
                        'error_type' => 'validation'
                    ];
                }
            }

            $firstname = $data['firstname'] ?? $existing['firstname'];
            $middlename = $data['middlename'] ?? $existing['middlename'];
            $lastname = $data['lastname'] ?? $existing['lastname'];
            $birthdate = $data['birthdate'] ?? $existing['birthdate'];
            $gender = $data['gender'] ?? $existing['gender'];
            $age = isset($data['age']) ? (int)$data['age'] : (int)$existing['age'];
            $status = $data['status'] ?? $existing['status'];

            $query = "UPDATE " . $this->table . " SET firstname = ?, middlename = ?, lastname = ?, birthdate = ?, gender = ?, age = ?, status = ? WHERE adult_id = ?";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }

            $stmt->bind_param('sssssiss', $firstname, $middlename, $lastname, $birthdate, $gender, $age, $status, $adult_id);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Adult updated successfully!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error updating adult: ' . $stmt->error,
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
     * Delete adult
     */
    public function delete($adult_id) {
        try {
            // Check if adult exists before deleting
            $existing = $this->getById($adult_id);
            if (!$existing) {
                return [
                    'success' => false,
                    'message' => 'Adult not found',
                    'error_type' => 'not_found'
                ];
            }

            $query = "DELETE FROM " . $this->table . " WHERE adult_id = ?";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }

            $stmt->bind_param('s', $adult_id);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Adult deleted successfully!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error deleting adult: ' . $stmt->error,
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
