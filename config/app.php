<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function app_flash(string $key, ?string $value = null): ?string
{
    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
        return null;
    }

    if (!empty($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }

    return null;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function current_user_role(): string
{
    return strtolower((string) (current_user()['role'] ?? 'user'));
}

function is_admin(): bool
{
    return current_user_role() === 'admin';
}

function require_login(string $redirect = '/login'): void
{
    if (!is_logged_in()) {
        app_flash('error', 'Please log in to continue.');
        header('Location: ' . $redirect);
        exit;
    }
}

function require_admin(string $redirect = '/login'): void
{
    require_login($redirect);

    if (!is_admin()) {
        app_flash('error', 'You do not have permission to access that page.');
        header('Location: /');
        exit;
    }
}

function allowed_sort_options(): array
{
    return [
        'newest' => 'items.created_at DESC',
        'name_asc' => 'items.name ASC',
        'name_desc' => 'items.name DESC',
        'price_asc' => 'items.price ASC',
        'price_desc' => 'items.price DESC',
        'rating_desc' => 'items.rating DESC',
    ];
}

function slugify(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value);
    $value = trim($value, '-');

    return $value !== '' ? $value : 'item-' . time();
}

function asset_data_uri(string $relativePath): string
{
    $relativePath = ltrim($relativePath, '/');
    $candidates = [
        __DIR__ . '/../' . $relativePath,
        __DIR__ . '/../public/' . ltrim($relativePath, './'),
    ];

    $fullPath = null;
    foreach ($candidates as $candidate) {
        if (is_file($candidate)) {
            $fullPath = $candidate;
            break;
        }
    }

    if ($fullPath === null) {
        return '';
    }

    $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
    return 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($fullPath));
}

function image_url(string $relativePath): string
{
    return '/public/index.php?asset=image&path=' . rawurlencode(ltrim($relativePath, '/'));
}

function current_path(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    if ($path !== '/' && str_ends_with($path, '/')) {
        $path = rtrim($path, '/');
    }

    return $path;
}

function product_url(string $slug): string
{
    return '/products/' . rawurlencode($slug);
}

function category_url(string $slug): string
{
    return '/products/category/' . rawurlencode($slug);
}

function send_image_file(string $relativePath): void
{
    $relativePath = ltrim($relativePath, '/');
    $candidates = [
        __DIR__ . '/../' . $relativePath,
        __DIR__ . '/../public/' . $relativePath,
    ];

    $filePath = null;
    foreach ($candidates as $candidate) {
        $real = realpath($candidate);
        if ($real && str_starts_with($real, realpath(__DIR__ . '/../public'))) {
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
    exit;
}
