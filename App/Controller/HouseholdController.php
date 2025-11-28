<?php
/**
 * Household Controller
 * API endpoints for household CRUD operations
 * 
 * SQL Structure:
 * CREATE TABLE households (
 *   household_id VARCHAR(20) PRIMARY KEY,
 *   family_no INT NOT NULL,
 *   full_name VARCHAR(150) NOT NULL,
 *   address VARCHAR(255) NOT NULL,
 *   income DECIMAL(12,2) DEFAULT 0.00
 * );
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../Config/Auth.php';
require_once __DIR__ . '/../Model/Household.php';

// require authenticated user
if (!isAuthenticated()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$householdModel = new Household();
$action = isset($_GET['action']) ? $_GET['action'] : null;

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

function handleCreate() {
    global $householdModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    // Required fields: household_id, family_no, full_name, address
    if (empty($data['household_id']) || empty($data['family_no']) || empty($data['full_name']) || empty($data['address'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Household ID, Family No, Full Name, and Address are required']);
        return;
    }

    $household_id = $data['household_id'];
    $family_no = $data['family_no'];
    $full_name = $data['full_name'];
    $address = $data['address'];
    $income = $data['income'] ?? 0.00;

    $result = $householdModel->create($household_id, $family_no, $full_name, $address, $income);
    echo json_encode($result);
    exit;
}

function handleUpdate() {
    global $householdModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['household_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Household ID is required']);
        return;
    }

    $household_id = $data['household_id'];
    $family_no = $data['family_no'] ?? 0;
    $full_name = $data['full_name'] ?? '';
    $address = $data['address'] ?? '';
    $income = $data['income'] ?? 0.00;

    $result = $householdModel->update($household_id, $family_no, $full_name, $address, $income);
    echo json_encode($result);
    exit;
}

function handleDelete() {
    global $householdModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['household_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Household ID is required']);
        return;
    }

    $household_id = $data['household_id'];
    $result = $householdModel->delete($household_id);
    echo json_encode($result);
}

function handleGetAll() {
    global $householdModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }

    $rows = $householdModel->getAll();
    echo json_encode(['success' => true, 'data' => $rows]);
}

function handleGetById() {
    global $householdModel;

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }

    if (empty($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Household ID is required']);
        return;
    }

    $household_id = $_GET['id'];
    $row = $householdModel->getById($household_id);
    if ($row) {
        echo json_encode(['success' => true, 'data' => $row]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Household not found']);
    }
}

?>
