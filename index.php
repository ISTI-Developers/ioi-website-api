<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require __DIR__ . '/controller/teamcontroller.php';
require __DIR__ . '/controller/rolescontroller.php';
require __DIR__ . '/controller/clientcontroller.php';
require __DIR__ . '/controller/projectcontroller.php';
require __DIR__ . '/controller/careercontroller.php';

$routes = [
    "team" => new TeamController(),
    "roles" => new RolesController(),
    "clients" => new ClientController(),
    "projects" => new ProjectController(),

    "careers" => new CareerController(),
];

$resource = $_GET['resource'] ?? null;

if(!$resource || !isset($routes[$resource])) {
    http_response_code(404);
    echo json_encode(["error" => "Resource not found"]);
    exit;
}

$controller = $routes[$resource];

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        if(isset($_GET['id'])) {
            $controller->getOne($_GET['id']);
        } else {
            $controller->get();
        }
        break;

    case "POST":
        if (!isset($_REQUEST['data'])) {
            $controller->send(["message" => "Data not found."], 404);
        }
        $data = json_decode($_REQUEST['data'], associative: true);

        if (isset($_FILES)) {
            $files = $_FILES['file'] ?? null;
            $filenames = [];

            if ($files && is_array($files['name'])) {
                $uploadDir = "uploads/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                foreach ($files['name'] as $index => $name) {
                    if ($files['error'][$index] === UPLOAD_ERR_OK) {
                        $fileName = time() . '_' . basename($name);
                        $filePath = "uploads/" . $fileName;
                        move_uploaded_file($files['tmp_name'][$index], __DIR__ . "/uploads/" . $fileName);
                        $filenames[] = $filePath;
                    }
                }
                $data["file"] = $filenames;
            }
        }

        $response = $controller->add($data);
        if ($response)
            $controller->send([
                "message" => "Added successfully.",
                "id" => $response
            ]);
        break;

    case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);
        $controller->update($data);
        break;

    case "DELETE":
        $controller->delete();
        break;
}