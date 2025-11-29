<?php
require_once __DIR__ . '/../Model/Resident.php';

class ResidentController {
    private $residentModel;

    public function __construct() {
        $this->residentModel = new Resident();
    }

    /**
     * Create Resident
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, 'Invalid request method', null, 405);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if (!isset($data['full_name']) || empty(trim($data['full_name']))) {
            $this->sendResponse(false, 'Full Name is required', null, 400);
            return;
        }
        if (!isset($data['birthdate']) || empty(trim($data['birthdate']))) {
            $this->sendResponse(false, 'Birthdate is required', null, 400);
            return;
        }
        if (!isset($data['gender']) || empty(trim($data['gender']))) {
            $this->sendResponse(false, 'Gender is required', null, 400);
            return;
        }
        if (!isset($data['household_id']) || empty($data['household_id'])) {
            $this->sendResponse(false, 'Household ID is required', null, 400);
            return;
        }

        $result = $this->residentModel->create(
            trim($data['full_name']),
            trim($data['birthdate']),
            trim($data['gender']),
            isset($data['occupation']) ? trim($data['occupation']) : null,
            intval($data['household_id']),
            isset($data['relation_to_head']) ? trim($data['relation_to_head']) : 'Other'
        );

        $this->sendResponse(
            $result['success'],
            $result['message'],
            isset($result['id']) ? ['id' => $result['id']] : null,
            $result['success'] ? 201 : 400
        );
    }

    /**
     * Update Resident
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, 'Invalid request method', null, 405);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id']) || empty($data['id'])) {
            $this->sendResponse(false, 'Resident ID is required', null, 400);
            return;
        }

        if (!isset($data['full_name']) || empty(trim($data['full_name']))) {
            $this->sendResponse(false, 'Full Name is required', null, 400);
            return;
        }
        if (!isset($data['birthdate']) || empty(trim($data['birthdate']))) {
            $this->sendResponse(false, 'Birthdate is required', null, 400);
            return;
        }
        if (!isset($data['gender']) || empty(trim($data['gender']))) {
            $this->sendResponse(false, 'Gender is required', null, 400);
            return;
        }
        if (!isset($data['household_id']) || empty($data['household_id'])) {
            $this->sendResponse(false, 'Household ID is required', null, 400);
            return;
        }

        $result = $this->residentModel->update(
            intval($data['id']),
            trim($data['full_name']),
            trim($data['birthdate']),
            trim($data['gender']),
            isset($data['occupation']) ? trim($data['occupation']) : null,
            intval($data['household_id']),
            isset($data['relation_to_head']) ? trim($data['relation_to_head']) : 'Other'
        );

        $this->sendResponse(
            $result['success'],
            $result['message'],
            null,
            $result['success'] ? 200 : 400
        );
    }

    /**
     * Delete Resident
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(false, 'Invalid request method', null, 405);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id']) || empty($data['id'])) {
            $this->sendResponse(false, 'Resident ID is required', null, 400);
            return;
        }

        $result = $this->residentModel->delete(intval($data['id']));

        $this->sendResponse(
            $result['success'],
            $result['message'],
            null,
            $result['success'] ? 200 : 400
        );
    }

    /**
     * Send JSON response
     */
    private function sendResponse($success, $message, $data = null, $status_code = 200) {
        header('Content-Type: application/json');
        http_response_code($status_code);

        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
}

// Handle API requests
$action = isset($_GET['action']) ? $_GET['action'] : '';
$controller = new ResidentController();

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'update':
        $controller->update();
        break;
    case 'delete':
        $controller->delete();
        break;
    default:
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>
