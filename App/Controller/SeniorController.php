<?php
/**
 * Senior Controller
 * Handles API endpoints for senior citizen CRUD operations
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../Config/Auth.php';
require_once __DIR__ . '/../Model/Senior.php';

// Check if user is authenticated
if (!isAuthenticated()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Initialize Senior model
$seniorModel = new Senior();
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
 * Handle Create Senior
 */
function handleCreate() {
    global $seniorModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (empty($data['firstname']) || empty($data['lastname']) || empty($data['birthdate']) || 
        empty($data['gender']) || empty($data['age']) || empty($data['status'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'All required fields must be provided']);
        exit;
    }

    $result = $seniorModel->create($data);
    echo json_encode($result);
    exit;
}

/**
 * Handle Update Senior
 */
function handleUpdate() {
    global $seniorModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (empty($data['senior_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Senior ID is required']);
        exit;
    }

    $senior_id = $data['senior_id'];
    $result = $seniorModel->update($senior_id, $data);
    echo json_encode($result);
    exit;
}

/**
 * Handle Delete Senior
 */
function handleDelete() {
    global $seniorModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (empty($data['senior_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Senior ID is required']);
        exit;
    }

    $senior_id = $data['senior_id'];
    $result = $seniorModel->delete($senior_id);
    echo json_encode($result);
    exit;
}

/**
 * Handle Get All Seniors
 */
function handleGetAll() {
    global $seniorModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    $seniors = $seniorModel->getAll();
    echo json_encode(['success' => true, 'data' => $seniors]);
    exit;
}

/**
 * Handle Get Senior By ID
 */
function handleGetById() {
    global $seniorModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    if (empty($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Senior ID is required']);
        exit;
    }

    $id = $_GET['id'];
    $senior = $seniorModel->getById($id);
    
    if ($senior === null) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Senior citizen not found']);
    } else {
        echo json_encode(['success' => true, 'data' => $senior]);
    }
    exit;
}
?>
