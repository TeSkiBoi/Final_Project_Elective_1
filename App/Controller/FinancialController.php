<?php
/**
 * Financial Controller
 * Handles CRUD operations for financial transactions
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../Model/Financial.php';

class FinancialController {
    private $model;

    public function __construct() {
        $this->model = new Financial();
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
            case 'getSummary':
                $this->getSummary();
                break;
            case 'getByType':
                $this->getByType();
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    }

    /**
     * Create new transaction
     */
    private function create() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            // Validation
            if (empty($data['transaction_date']) || empty($data['transaction_type']) || 
                empty($data['category']) || empty($data['amount'])) {
                echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
                return;
            }

            // Validate amount
            if (!is_numeric($data['amount']) || $data['amount'] <= 0) {
                echo json_encode(['success' => false, 'message' => 'Amount must be a positive number']);
                return;
            }

            // Validate transaction type
            if (!in_array($data['transaction_type'], ['Income', 'Expense'])) {
                echo json_encode(['success' => false, 'message' => 'Invalid transaction type']);
                return;
            }

            $result = $this->model->create($data);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Transaction created successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to create transaction'
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
     * Update transaction
     */
    private function update() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['transaction_id'])) {
                echo json_encode(['success' => false, 'message' => 'Transaction ID is required']);
                return;
            }

            // Validation
            if (empty($data['transaction_date']) || empty($data['transaction_type']) || 
                empty($data['category']) || empty($data['amount'])) {
                echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
                return;
            }

            // Validate amount
            if (!is_numeric($data['amount']) || $data['amount'] <= 0) {
                echo json_encode(['success' => false, 'message' => 'Amount must be a positive number']);
                return;
            }

            $result = $this->model->update($data['transaction_id'], $data);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Transaction updated successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update transaction'
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
     * Delete transaction
     */
    private function delete() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['transaction_id'])) {
                echo json_encode(['success' => false, 'message' => 'Transaction ID is required']);
                return;
            }

            $result = $this->model->delete($data['transaction_id']);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Transaction deleted successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to delete transaction'
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
     * Get transaction by ID
     */
    private function getById() {
        try {
            $id = $_GET['id'] ?? null;

            if (empty($id)) {
                echo json_encode(['success' => false, 'message' => 'Transaction ID is required']);
                return;
            }

            $transaction = $this->model->getById($id);

            if ($transaction) {
                echo json_encode([
                    'success' => true,
                    'data' => $transaction
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Transaction not found'
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
     * Get financial summary
     */
    private function getSummary() {
        try {
            $summary = $this->model->getSummary();
            echo json_encode([
                'success' => true,
                'data' => $summary
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get transactions by type
     */
    private function getByType() {
        try {
            $type = $_GET['type'] ?? '';

            if (!in_array($type, ['Income', 'Expense'])) {
                echo json_encode(['success' => false, 'message' => 'Invalid transaction type']);
                return;
            }

            $transactions = $this->model->getByType($type);
            echo json_encode([
                'success' => true,
                'data' => $transactions
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
$controller = new FinancialController();
$controller->handleRequest();
