<?php


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

//
$routes = [
    "team" => new TeamController(),
    "roles" => new RolesController(),
    "clients" => new ClientController(),
    "projects" => new ProjectController(),

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
                        move_uploaded_file($files['tmp_name'][$index], $filePath);
                        $filenames[] = $filePath;
                    }
                }
                $data["file"] = $filenames;
            }
        }
        if (isset($_REQUEST['type'])) {
            switch ($_REQUEST['type']) {
                case "batch":
                    $controller->addMultiple($data);
                    break;
                case "images":
                    $controller->addImages($data);

            }
        }
        $response = $controller->add($data);
        if ($response)
            $controller->send([
                "message" => "Insurance added successfully.",
                "id" => $response
            ]);
        break;
}