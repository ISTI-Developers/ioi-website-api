<?php


function setCorsHeaders(): void {
    $allowed_origins = [
        'http://localhost:5173', // vite dev server 
         'http://localhost:9917', // vite dev server
        'https://domain.com', // production
    ];

    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

    if(in_array($origin, $allowed_origins)) {
        header("Access-Control-Allow-Origin: $origin");
        header('Access-Control-Allow-Credentials: true');

    }

    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Content-Type: application/json');

    if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit;
    }

}
