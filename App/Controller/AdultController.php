<?php
/**
 * Adult Controller
 * Handles API endpoints for adult CRUD operations
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../Config/Auth.php';
require_once __DIR__ . '/../Model/Adult.php';

// Check if user is authenticated
if (!isAuthenticated()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Initialize Adult model
$adultModel = new Adult();
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
 * Handle Create Adult
 */
function handleCreate() {
    global $adultModel;

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

    $result = $adultModel->create($data);
    echo json_encode($result);
    exit;
}

/**
 * Handle Update Adult
 */
function handleUpdate() {
    global $adultModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (empty($data['adult_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Adult ID is required']);
        exit;
    }

    $adult_id = $data['adult_id'];
    $result = $adultModel->update($adult_id, $data);
    echo json_encode($result);
    exit;
}

/**
 * Handle Delete Adult
 */
function handleDelete() {
    global $adultModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (empty($data['adult_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Adult ID is required']);
        exit;
    }

    $adult_id = $data['adult_id'];
    $result = $adultModel->delete($adult_id);
    echo json_encode($result);
    exit;
}

/**
 * Handle Get All Adults
 */
function handleGetAll() {
    global $adultModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    $adults = $adultModel->getAll();
    echo json_encode(['success' => true, 'data' => $adults]);
    exit;
}

/**
 * Handle Get Adult By ID
 */
function handleGetById() {
    global $adultModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    if (empty($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Adult ID is required']);
        exit;
    }

    $id = $_GET['id'];
    $adult = $adultModel->getById($id);
    
    if ($adult === null) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Adult not found']);
    } else {
        echo json_encode(['success' => true, 'data' => $adult]);
    }
    exit;
}
?>
