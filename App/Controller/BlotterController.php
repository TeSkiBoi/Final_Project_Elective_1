<?php
/**
 * Blotter Controller
 * Handles CRUD operations for blotter incidents
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../Model/Blotter.php';

class BlotterController {
    private $model;

    public function __construct() {
        $this->model = new Blotter();
    }

    /**
     * Handle incoming requests
     */
    public function handleRequest() {
        $action = $_GET['action'] ?? '';

        switch ($action) {
            case 'create':
                $this->create();
                break;
            case 'update':
                $this->update();
                break;
            case 'delete':
                $this->delete();
                break;
            case 'getById':
                $this->getById();
                break;
            case 'getStatistics':
                $this->getStatistics();
                break;
            case 'getByStatus':
                $this->getByStatus();
                break;
            case 'generateCaseNumber':
                $this->generateCaseNumber();
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    }

    /**
     * Create new incident
     */
    private function create() {
        try {
            // Check if data is from FormData or JSON
            if (!empty($_POST)) {
                $data = $_POST;
            } else {
                $data = json_decode(file_get_contents('php://input'), true);
            }

            // Validation
            if (empty($data['case_number']) || empty($data['complainant_name']) || 
                empty($data['respondent_name']) || empty($data['incident_description'])) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Case number, complainant name, respondent name, and incident description are required'
                ]);
                return;
            }

            // Check if case number already exists
            if ($this->model->caseNumberExists($data['case_number'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Case number already exists. Please use a different case number.'
                ]);
                return;
            }

            $result = $this->model->create($data);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Incident recorded successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to record incident'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update incident
     */
    private function update() {
        try {
            // Check if data is from FormData or JSON
            if (!empty($_POST)) {
                $data = $_POST;
            } else {
                $data = json_decode(file_get_contents('php://input'), true);
            }

            if (empty($data['incident_id'])) {
                echo json_encode(['success' => false, 'message' => 'Incident ID is required']);
                return;
            }

            // Validation
            if (empty($data['case_number']) || empty($data['complainant_name']) || 
                empty($data['respondent_name']) || empty($data['incident_description'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Case number, complainant name, respondent name, and incident description are required'
                ]);
                return;
            }

            // Check if case number already exists (excluding current incident)
            if ($this->model->caseNumberExists($data['case_number'], $data['incident_id'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Case number already exists. Please use a different case number.'
                ]);
                return;
            }

            $result = $this->model->update($data['incident_id'], $data);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Incident updated successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update incident'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete incident
     */
    private function delete() {
        try {
            // Check if data is from FormData or JSON
            if (!empty($_POST)) {
                $data = $_POST;
            } else {
                $data = json_decode(file_get_contents('php://input'), true);
            }

            if (empty($data['incident_id'])) {
                echo json_encode(['success' => false, 'message' => 'Incident ID is required']);
                return;
            }

            $result = $this->model->delete($data['incident_id']);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Incident deleted successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to delete incident'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get incident by ID
     */
    private function getById() {
        try {
            $id = $_GET['id'] ?? null;

            if (empty($id)) {
                echo json_encode(['success' => false, 'message' => 'Incident ID is required']);
                return;
            }

            $incident = $this->model->getById($id);

            if ($incident) {
                echo json_encode([
                    'success' => true,
                    'data' => $incident
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Incident not found'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get incident statistics
     */
    private function getStatistics() {
        try {
            $statistics = $this->model->getStatistics();
            echo json_encode([
                'success' => true,
                'data' => $statistics
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get incidents by status
     */
    private function getByStatus() {
        try {
            $status = $_GET['status'] ?? '';

            if (empty($status)) {
                echo json_encode(['success' => false, 'message' => 'Status is required']);
                return;
            }

            $incidents = $this->model->getByStatus($status);
            echo json_encode([
                'success' => true,
                'data' => $incidents
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generate new case number
     */
    private function generateCaseNumber() {
        try {
            $caseNumber = $this->model->generateCaseNumber();
            echo json_encode([
                'success' => true,
                'case_number' => $caseNumber
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}

// Initialize controller and handle request
$controller = new BlotterController();
$controller->handleRequest();
