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
require __DIR__ . '/controller/projectpointscontroller.php';
require __DIR__ . '/controller/projectgallerycontroller.php';

require __DIR__ . '/controller/careercontroller.php';
require __DIR__ . '/controller/bannercontroller.php';

$routes = [
    "team" => new TeamController(),
    "roles" => new RolesController(),
    "clients" => new ClientController(),

    "projects" => new ProjectController(),
    "points" => new ProjectPointsController(),
    "gallery" => new ProjectGalleryController(),

    "careers" => new CareerController(),
    "banners" => new BannerController(),
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
            // Read JSON input from request body
            $data = json_decode(file_get_contents("php://input"), true);
        
            if (!$data) {
                $controller->send(["message" => "Data not found."], 400);
            }
        
            // Pass data to controller
            $response = $controller->add($data);
            if ($response) {
                $controller->send([
                    "message" => "Added successfully.",
                    "id" => $response
                ]);
            }
            break;

    case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);
        $controller->update($data);
        break;
}