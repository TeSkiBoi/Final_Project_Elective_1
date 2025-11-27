<?php
/**
 * Senior Model
 * Handles database operations for senior citizens
 */

require_once __DIR__ . '/../Config/Database.php';

class Senior {
    private $connection;
    private $table = 'seniors';

    public function __construct() {
        $database = new Database();
        $this->connection = $database->connect();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY lastname ASC";
        $result = $this->connection->query($query);

        if (!$result) return false;

        $rows = [];
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
        return $rows;
    }

    public function getById($senior_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE senior_id = ? LIMIT 1";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) return null;
        $stmt->bind_param('s', $senior_id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res ? $res->fetch_assoc() : null;
    }

    private function generateSeniorId() {
        $query = "SELECT senior_id FROM " . $this->table . " WHERE senior_id LIKE 'S%' ORDER BY senior_id DESC LIMIT 1";
        $result = $this->connection->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastId = $row['senior_id'];
            $number = (int)substr($lastId, 1) + 1;
        } else {
            $number = 1;
        }

        return 'S' . str_pad($number, 9, '0', STR_PAD_LEFT);
    }

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

            // Validate age is at least 60
            if ((int)$data['age'] < 60) {
                return [
                    'success' => false,
                    'message' => 'Senior citizens must be 60 years or older',
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

            // Validate status enum
            $validStatuses = ['Active', 'Inactive', 'Deceased'];
            if (!in_array($data['status'], $validStatuses)) {
                return [
                    'success' => false,
                    'message' => 'Invalid status value',
                    'error_type' => 'validation'
                ];
            }

            $senior_id = !empty($data['senior_id']) ? $data['senior_id'] : $this->generateSeniorId();

            // Check for duplicate senior_id
            $check = $this->getById($senior_id);
            if ($check) {
                return [
                    'success' => false,
                    'message' => 'Senior ID already exists',
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

            $query = "INSERT INTO " . $this->table . " (senior_id, firstname, middlename, lastname, birthdate, gender, age, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }

            $stmt->bind_param('ssssssis', $senior_id, $firstname, $middlename, $lastname, $birthdate, $gender, $age, $status);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Senior citizen created successfully!',
                    'senior_id' => $senior_id
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error creating senior citizen: ' . $stmt->error,
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

    public function update($senior_id, $data) {
        try {
            // Validate senior exists
            $existing = $this->getById($senior_id);
            if (!$existing) {
                return [
                    'success' => false,
                    'message' => 'Senior citizen not found',
                    'error_type' => 'not_found'
                ];
            }

            // Validate age if provided
            if (isset($data['age']) && (int)$data['age'] < 60) {
                return [
                    'success' => false,
                    'message' => 'Senior citizens must be 60 years or older',
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
                $validStatuses = ['Active', 'Inactive', 'Deceased'];
                if (!in_array($data['status'], $validStatuses)) {
                    return [
                        'success' => false,
                        'message' => 'Invalid status value',
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

            $query = "UPDATE " . $this->table . " SET firstname = ?, middlename = ?, lastname = ?, birthdate = ?, gender = ?, age = ?, status = ? WHERE senior_id = ?";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }

            $stmt->bind_param('sssssiss', $firstname, $middlename, $lastname, $birthdate, $gender, $age, $status, $senior_id);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Senior citizen updated successfully!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error updating senior citizen: ' . $stmt->error,
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

    public function delete($senior_id) {
        try {
            // Check if senior exists before deleting
            $existing = $this->getById($senior_id);
            if (!$existing) {
                return [
                    'success' => false,
                    'message' => 'Senior citizen not found',
                    'error_type' => 'not_found'
                ];
            }

            $query = "DELETE FROM " . $this->table . " WHERE senior_id = ?";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                return [
                    'success' => false,
                    'message' => 'Error preparing statement: ' . $this->connection->error,
                    'error_type' => 'database'
                ];
            }

            $stmt->bind_param('s', $senior_id);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Senior citizen deleted successfully!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error deleting senior citizen: ' . $stmt->error,
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
