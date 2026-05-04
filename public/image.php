<?php

require_once __DIR__ . '/../config/app.php';

$path = trim($_GET['path'] ?? '');
$path = ltrim($path, '/');

$candidates = [
    __DIR__ . '/' . $path,
    __DIR__ . '/../' . $path,
];

$filePath = null;
foreach ($candidates as $candidate) {
    $real = realpath($candidate);
    if ($real && str_starts_with($real, realpath(__DIR__))) {
        $filePath = $real;
        break;
    }
}

if ($filePath === null || !is_file($filePath)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Image not found';
    exit;
}

$mime = mime_content_type($filePath) ?: 'application/octet-stream';
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($filePath));
readfile($filePath);
