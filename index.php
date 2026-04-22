<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/cors.php'; 

require __DIR__ . '/controller/teamcontroller.php';
require __DIR__ . '/controller/rolescontroller.php';
require __DIR__ . '/controller/clientcontroller.php';


require __DIR__ . '/controller/projectcontroller.php';
require __DIR__ . '/controller/project_pointscontroller.php';
require __DIR__ . '/controller/project_gallerycontroller.php';
require __DIR__ . '/controller/project_prosecontroller.php';
require __DIR__ . '/controller/project_videocontroller.php';


require __DIR__ . '/controller/careercontroller.php';
require __DIR__ . '/controller/bannercontroller.php';

setCorsHeaders();


$routes = [

    "team" => new TeamController(),
    "roles" => new RolesController(),
    "clients" => new ClientController(),

    "projects" => new ProjectController(),
    "points" => new ProjectPointsController(),
    "gallery" => new ProjectGalleryController(),
    "prose" => new ProjectProseController(),
    "video" => new ProjectVideoController(),

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
      $hasFiles = !empty($_FILES['file']['name'][0]) || !empty($_FILES['file']['name'][0]) || !empty($_FILES['file']['name']);

        if ($hasFiles) {
            $controller->add();
            break;
        }


        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            $controller->send(["message" => "Data not found."], 400);
            exit;
        }

        
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

    case "DELETE": 
        $controller->delete();
        break;
}
