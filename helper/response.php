<?php

function respond(int $statusCode, array $data): void {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

function respondSuccess(array $data, int $statusCode = 200): void {
    respond($statusCode, $data);
}

function respondError(string $message, int $statusCode = 400): void {
    respond($statusCode, ['error' => $message]);
}

function getRequestBody(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    if(!is_array($data)) {
        respondError('Invalid JSON body', 400);
    }

    return $data;
}
