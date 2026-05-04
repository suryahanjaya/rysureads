<?php

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: create_item.php');
    exit;
}

require_admin();

$name        = trim($_POST['name'] ?? '');
$price       = trim($_POST['price'] ?? '');
$categoryId  = (int) ($_POST['category_id'] ?? 0);
$description = trim($_POST['description'] ?? '');

if ($name === '' || $price === '' || $categoryId === 0 || $description === '') {
    app_flash('error', 'All required fields must be completed.');
    header('Location: create_item.php');
    exit;
}

// --- Handle image upload ---
$imageValue = null;
$uploadFile = $_FILES['image'] ?? null;

if ($uploadFile && $uploadFile['error'] === UPLOAD_ERR_OK && $uploadFile['size'] > 0) {
    $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $finfo   = new finfo(FILEINFO_MIME_TYPE);
    $mime    = $finfo->file($uploadFile['tmp_name']);

    if (!in_array($mime, $allowed, true)) {
        app_flash('error', 'Only JPG, PNG, WebP, and GIF images are allowed.');
        header('Location: create_item.php');
        exit;
    }

    if ($uploadFile['size'] > 5 * 1024 * 1024) {
        app_flash('error', 'Image must be smaller than 5 MB.');
        header('Location: create_item.php');
        exit;
    }

    $ext        = pathinfo($uploadFile['name'], PATHINFO_EXTENSION) ?: 'jpg';
    $safeName   = 'item_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
    $uploadDir  = __DIR__ . '/../public/images/';
    $destPath   = $uploadDir . $safeName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!move_uploaded_file($uploadFile['tmp_name'], $destPath)) {
        app_flash('error', 'Failed to save the uploaded image. Please try again.');
        header('Location: create_item.php');
        exit;
    }

    $imageValue = 'images/' . $safeName;
}

$slug   = slugify($name);
$rating = 4.5;

$stmt = $conn->prepare('INSERT INTO items (name, slug, price, rating, category_id, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param('ssddiss', $name, $slug, $price, $rating, $categoryId, $description, $imageValue);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header('Location: items.php');
    exit;
}

$stmt->close();
$conn->close();
app_flash('error', 'Unable to save the item. Please try again.');
header('Location: create_item.php');
exit;
