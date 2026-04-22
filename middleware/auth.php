<?php

require_once __DIR__ . '/../config/session.php';

function requireAuth(): array {
    startSecureSession();

    if(empty($_SESSION['id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthenticated. Please log in']);
        exit;
    }

    return [
        'user_id' => $_SESSION['id'],
        'username' => $_SESSION['username'],
        'name' => $_SESSION['name']
    ];
}
