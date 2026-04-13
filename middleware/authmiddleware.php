<?php

require_once __DIR__ . '/../helper/JWT.php';


function authenticate() {

    $headers = getallheaders();
    $jwtHeader = $headers['Authorization'] ?? '';

    if(!isset($jwtHeader)) {
        http_response_code(401);
        echo json_encode(['error' => 'No token provided']);
        exit;
    }

    $token = str_replace('Bearer ', '', $jwtHeader);

    $jwt     = new JWT();
    $payload = $jwt->getPayload($token);

    if(!$payload) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid or expired token']);
        exit;
    }

    if(!isset($payload['role']) || $payload['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        exit;
    }

    return $payload;
}