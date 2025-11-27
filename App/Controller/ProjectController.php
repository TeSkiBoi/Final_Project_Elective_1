<?php
/**
 * Project Controller
 * Handles CRUD operations for barangay projects
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../Model/Project.php';

class ProjectController {
    private $model;

    public function __construct() {
        $this->model = new Project();
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
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    }

    /**
     * Create new project
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
            if (empty($data['project_name']) || empty($data['proponent'])) {
                echo json_encode(['success' => false, 'message' => 'Project name and proponent are required']);
                return;
            }

            // Calculate budget remaining
            $total_budget = floatval($data['total_budget'] ?? 0);
            $budget_utilized = floatval($data['budget_utilized'] ?? 0);
            $data['budget_remaining'] = $total_budget - $budget_utilized;

            $result = $this->model->create($data);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Project created successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to create project'
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
     * Update project
     */
    private function update() {
        try {
            // Check if data is from FormData or JSON
            if (!empty($_POST)) {
                $data = $_POST;
            } else {
                $data = json_decode(file_get_contents('php://input'), true);
            }

            if (empty($data['project_id'])) {
                echo json_encode(['success' => false, 'message' => 'Project ID is required']);
                return;
            }

            // Validation
            if (empty($data['project_name']) || empty($data['proponent'])) {
                echo json_encode(['success' => false, 'message' => 'Project name and proponent are required']);
                return;
            }

            // Calculate budget remaining
            $total_budget = floatval($data['total_budget'] ?? 0);
            $budget_utilized = floatval($data['budget_utilized'] ?? 0);
            $data['budget_remaining'] = $total_budget - $budget_utilized;

            $result = $this->model->update($data['project_id'], $data);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Project updated successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update project'
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
     * Delete project
     */
    private function delete() {
        try {
            // Check if data is from FormData or JSON
            if (!empty($_POST)) {
                $data = $_POST;
            } else {
                $data = json_decode(file_get_contents('php://input'), true);
            }

            if (empty($data['project_id'])) {
                echo json_encode(['success' => false, 'message' => 'Project ID is required']);
                return;
            }

            $result = $this->model->delete($data['project_id']);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Project deleted successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to delete project'
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
     * Get project by ID
     */
    private function getById() {
        try {
            $id = $_GET['id'] ?? null;

            if (empty($id)) {
                echo json_encode(['success' => false, 'message' => 'Project ID is required']);
                return;
            }

            $project = $this->model->getById($id);

            if ($project) {
                echo json_encode([
                    'success' => true,
                    'data' => $project
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Project not found'
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
     * Get project statistics
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
     * Get projects by status
     */
    private function getByStatus() {
        try {
            $status = $_GET['status'] ?? '';

            if (empty($status)) {
                echo json_encode(['success' => false, 'message' => 'Status is required']);
                return;
            }

            $projects = $this->model->getByStatus($status);
            echo json_encode([
                'success' => true,
                'data' => $projects
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
$controller = new ProjectController();
$controller->handleRequest();
