<?php
/**
 * BarangayOfficialController
 * Handles HTTP requests for barangay officials organizational chart
 */

// Start output buffering to prevent premature output
ob_start();

// Prevent HTML error output but still log errors
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Custom error handler to prevent any HTML output
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // Don't throw exception for suppressed errors
    if (!(error_reporting() & $errno)) {
        return false;
    }
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// Set headers immediately before any output
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    require_once __DIR__ . '/../Model/BarangayOfficial.php';
} catch (Exception $e) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Failed to load required files: ' . $e->getMessage()]);
    ob_end_flush();
    exit;
}

class BarangayOfficialController {
    private $officialModel;

    public function __construct() {
        $this->officialModel = new BarangayOfficial();
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? '';

        try {
            switch ($action) {
                case 'getAll':
                    $this->getAll();
                    break;
                case 'getById':
                    $this->getById();
                    break;
                case 'updateName':
                    $this->updateName();
                    break;
                case 'updateImage':
                    $this->updateImage();
                    break;
                case 'update':
                    $this->update();
                    break;
                case 'create':
                    $this->create();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                case 'updateOrder':
                    $this->updateOrder();
                    break;
                default:
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Invalid action']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
    }

    private function getAll() {
        $officials = $this->officialModel->getAll();
        echo json_encode(['success' => true, 'data' => $officials]);
    }

    private function getById() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Official ID is required']);
            return;
        }

        $official = $this->officialModel->getById($id);
        
        if ($official) {
            echo json_encode(['success' => true, 'data' => $official]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Official not found']);
        }
    }

    private function updateName() {
        // Clear any previous output
        if (ob_get_length()) ob_clean();
        
        // Get data from POST or JSON
        $input = json_decode(file_get_contents('php://input'), true);
        $data = $input ?? $_POST;

        $id = $data['id'] ?? null;
        $full_name = $data['full_name'] ?? null;

        // Validation
        if (!$id || !$full_name) {
            echo json_encode(['success' => false, 'message' => 'ID and full name are required']);
            return;
        }

        // Validate name (letters, spaces, and common punctuation only)
        if (!preg_match("/^[a-zA-Z\s\.\-']+$/", $full_name)) {
            echo json_encode(['success' => false, 'message' => 'Invalid name format']);
            return;
        }

        $result = $this->officialModel->updateName($id, $full_name);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Official name updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update official name']);
        }
    }

    private function updateImage() {
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Official ID is required']);
            return;
        }

        // Check if file was uploaded
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'No image uploaded or upload error']);
            return;
        }

        $file = $_FILES['image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Validate file type
        if (!in_array($file['type'], $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and GIF allowed']);
            return;
        }

        // Validate file size
        if ($file['size'] > $maxSize) {
            echo json_encode(['success' => false, 'message' => 'File size exceeds 5MB limit']);
            return;
        }

        // Create upload directory if it doesn't exist
        $uploadDir = __DIR__ . '/../../assets/uploads/officials/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'official_' . $id . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $filename;

        // Get old image to delete later
        $official = $this->officialModel->getById($id);
        $oldImage = $official['image_path'] ?? null;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Update database with new image path
            $imagePath = 'officials/' . $filename;
            $result = $this->officialModel->updateImage($id, $imagePath);

            if ($result) {
                // Delete old image if it exists and is not a default image
                if ($oldImage && strpos($oldImage, 'default_') === false) {
                    $oldImagePath = __DIR__ . '/../../assets/uploads/' . $oldImage;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                echo json_encode([
                    'success' => true, 
                    'message' => 'Image uploaded successfully',
                    'image_path' => $imagePath
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update database']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
        }
    }

    private function update() {
        $input = json_decode(file_get_contents('php://input'), true);
        $data = $input ?? $_POST;

        $id = $data['id'] ?? null;
        $full_name = $data['full_name'] ?? null;
        $image_path = $data['image_path'] ?? 'default.png';
        $display_order = $data['display_order'] ?? 0;

        if (!$id || !$full_name) {
            echo json_encode(['success' => false, 'message' => 'ID and full name are required']);
            return;
        }

        $updateData = [
            'full_name' => $full_name,
            'image_path' => $image_path,
            'display_order' => $display_order
        ];

        $result = $this->officialModel->update($id, $updateData);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Official updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update official']);
        }
    }

    private function create() {
        $input = json_decode(file_get_contents('php://input'), true);
        $data = $input ?? $_POST;

        $position_title = $data['position_title'] ?? null;
        $full_name = $data['full_name'] ?? null;
        $image_path = $data['image_path'] ?? 'default.png';
        $display_order = $data['display_order'] ?? 0;
        $is_active = $data['is_active'] ?? 'Yes';

        if (!$position_title) {
            echo json_encode(['success' => false, 'message' => 'Position title is required']);
            return;
        }

        // Check if position already exists
        if ($this->officialModel->positionExists($position_title)) {
            echo json_encode(['success' => false, 'message' => 'Position already exists']);
            return;
        }

        $createData = [
            'position_title' => $position_title,
            'full_name' => $full_name,
            'image_path' => $image_path,
            'display_order' => $display_order,
            'is_active' => $is_active
        ];

        $result = $this->officialModel->create($createData);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Position created successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create position']);
        }
    }

    private function delete() {
        $input = json_decode(file_get_contents('php://input'), true);
        $data = $input ?? $_POST;

        $id = $data['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Official ID is required']);
            return;
        }

        $result = $this->officialModel->delete($id);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Official removed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove official']);
        }
    }

    private function updateOrder() {
        $input = json_decode(file_get_contents('php://input'), true);
        $data = $input ?? $_POST;

        $orders = $data['orders'] ?? null;

        if (!$orders || !is_array($orders)) {
            echo json_encode(['success' => false, 'message' => 'Order data is required']);
            return;
        }

        // Update each official's display order
        foreach ($orders as $order) {
            $id = $order['id'] ?? null;
            $display_order = $order['display_order'] ?? null;

            if ($id && $display_order !== null) {
                $this->officialModel->updateOrder($id, $display_order);
            }
        }

        echo json_encode(['success' => true, 'message' => 'Display order updated successfully']);
    }
}

// Initialize controller and handle request
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $controller = new BarangayOfficialController();
        $controller->handleRequest();
    } catch (Exception $e) {
        // Clear any previous output
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Controller error: ' . $e->getMessage()]);
    }
    
    // Flush output buffer and exit
    ob_end_flush();
    exit;
}