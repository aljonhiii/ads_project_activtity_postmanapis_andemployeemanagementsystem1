<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once __DIR__ . '/database.php';
include_once __DIR__ . '/../class/Employee.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

$emp = new Employee($db);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
            $employeeData = $emp->getEmployeeById($id);
            if ($employeeData) {
                echo json_encode([
                    "status" => "success",
                    "employee" => [$employeeData]
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Employee not found"
                ]);
            }
        } else {
            $emps = $emp->getAllEmployees();
            echo json_encode([
                "status" => "success",
                "employee" => $emps
            ]);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            echo json_encode(["status" => "error", "message" => "Invalid JSON input"]);
            exit();
        }

        if (
            isset($data['first_name'], $data['last_name'], $data['mobile_number'], $data['email'], $data['sex'], $data['job_title'])
        ) {
            $first_name = trim($data['first_name']);
            $last_name = trim($data['last_name']);
            $middle_initial = isset($data['middle_initial']) ? trim($data['middle_initial']) : '';
            $mobile_number = trim($data['mobile_number']);
            $email = trim($data['email']);
            $sex = trim($data['sex']);
            $job_title = trim($data['job_title']);

            $result = $emp->addEmployee($first_name, $last_name, $middle_initial, $mobile_number, $email, $sex, $job_title);
            if ($result) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Employee added successfully"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Failed to add employee"
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid input. Missing required fields."
            ]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            echo json_encode(["status" => "error", "message" => "Invalid JSON input"]);
            exit();
        }

        if (
            isset($data['id'], $data['first_name'], $data['last_name'], $data['mobile_number'], $data['email'], $data['sex'], $data['job_title'])
        ) {
            $id = intval($data['id']);
            $first_name = trim($data['first_name']);
            $last_name = trim($data['last_name']);
            $middle_initial = isset($data['middle_initial']) ? trim($data['middle_initial']) : '';
            $mobile_number = trim($data['mobile_number']);
            $email = trim($data['email']);
            $sex = trim($data['sex']);
            $job_title = trim($data['job_title']);

            $result = $emp->updateEmployee($id, $first_name, $last_name, $middle_initial, $mobile_number, $email, $sex, $job_title);
            if ($result) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Employee updated successfully"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Failed to update employee"
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid input. Missing required fields for update."
            ]);
        }
        break;

    case 'DELETE':
        $id = null;
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
        } else {
            $data = json_decode(file_get_contents("php://input"), true);
            if (isset($data['id'])) {
                $id = intval($data['id']);
            }
        }

        if ($id) {
            $result = $emp->deleteEmployee($id);
            if ($result) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Employee deleted successfully"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Failed to delete employee"
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid ID"
            ]);
        }
        break;

    default:
        echo json_encode([
            "status" => "error",
            "message" => "Invalid request method"
        ]);
        break;
}
?>
