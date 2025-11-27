<?php
/**
 * Child Controller
 * Handles API endpoints for child CRUD operations
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../Config/Auth.php';
require_once __DIR__ . '/../Model/Child.php';

// Check if user is authenticated
if (!isAuthenticated()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Initialize Child model
$childModel = new Child();
$action = isset($_GET['action']) ? $_GET['action'] : null;

// Handle different actions
switch ($action) {
    case 'create':
        handleCreate();
        break;
    case 'update':
        handleUpdate();
        break;
    case 'delete':
        handleDelete();
        break;
    case 'getAll':
        handleGetAll();
        break;
    case 'getById':
        handleGetById();
        break;
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

/**
 * Handle Create Child
 */
function handleCreate() {
    global $childModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (empty($data['firstname']) || empty($data['lastname']) || empty($data['birthdate']) || 
        empty($data['gender']) || empty($data['age'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'All required fields must be provided']);
        return;
    }

    $result = $childModel->create($data);
    echo json_encode($result);
}

/**
 * Handle Update Child
 */
function handleUpdate() {
    global $childModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (empty($data['child_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Child ID is required']);
        return;
    }

    $child_id = $data['child_id'];
    $result = $childModel->update($child_id, $data);
    echo json_encode($result);
}

/**
 * Handle Delete Child
 */
function handleDelete() {
    global $childModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (empty($data['child_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Child ID is required']);
        return;
    }

    $child_id = $data['child_id'];
    $result = $childModel->delete($child_id);
    echo json_encode($result);
}

/**
 * Handle Get All Children
 */
function handleGetAll() {
    global $childModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }

    $children = $childModel->getAll();
    echo json_encode(['success' => true, 'data' => $children]);
}

/**
 * Handle Get Child By ID
 */
function handleGetById() {
    global $childModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }

    if (empty($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Child ID is required']);
        return;
    }

    $id = $_GET['id'];
    $child = $childModel->getById($id);
    
    if ($child === null) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Child not found']);
    } else {
        echo json_encode(['success' => true, 'data' => $child]);
    }
}
?>
