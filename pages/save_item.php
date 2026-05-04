<?php

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /create-item');
    exit;
}

require_admin();

$name        = trim($_POST['name'] ?? '');
$price       = trim($_POST['price'] ?? '');
$rating      = trim($_POST['rating'] ?? '4.5');
$categoryId  = (int) ($_POST['category_id'] ?? 0);
$descEn      = trim($_POST['description_en'] ?? '');
$descZh      = trim($_POST['description_zh'] ?? '');

if ($name === '' || $price === '' || $categoryId === 0 || $descEn === '') {
    app_flash('error', 'All required fields must be completed.');
    header('Location: /create-item');
    exit;
}

$ratingVal = max(0, min(5, (float) $rating));

// --- Handle image upload ---
$imageValue = null;
$uploadFile = $_FILES['image'] ?? null;

if ($uploadFile && $uploadFile['error'] === UPLOAD_ERR_OK && $uploadFile['size'] > 0) {
    $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $finfo   = new finfo(FILEINFO_MIME_TYPE);
    $mime    = $finfo->file($uploadFile['tmp_name']);

    if (!in_array($mime, $allowed, true)) {
        app_flash('error', 'Only JPG, PNG, WebP, and GIF images are allowed.');
        header('Location: /create-item');
        exit;
    }

    if ($uploadFile['size'] > 5 * 1024 * 1024) {
        app_flash('error', 'Image must be smaller than 5 MB.');
        header('Location: /create-item');
        exit;
    }

    $ext       = pathinfo($uploadFile['name'], PATHINFO_EXTENSION) ?: 'jpg';
    $safeName  = 'item_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
    $uploadDir = __DIR__ . '/../public/images/';
    $destPath  = $uploadDir . $safeName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!move_uploaded_file($uploadFile['tmp_name'], $destPath)) {
        app_flash('error', 'Failed to save the uploaded image. Please try again.');
        header('Location: /create-item');
        exit;
    }

    $imageValue = 'images/' . $safeName;
}

$slug = slugify($name);

// Use ZH desc as main description; EN stored in description_en
$descMain = $descZh !== '' ? $descZh : $descEn;

$stmt = $conn->prepare('INSERT INTO items (name, slug, price, rating, category_id, description, description_en, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param('ssddiiss', $name, $slug, $price, $ratingVal, $categoryId, $descMain, $descEn, $imageValue);

if (!$stmt->execute()) {
    $stmt->close();
    $conn->close();
    app_flash('error', 'Unable to save the item. The title may already exist.');
    header('Location: /create-item');
    exit;
}

$newItemId = $stmt->insert_id;
$stmt->close();

// --- Handle store locations ---
$locNames = $_POST['loc_name']    ?? [];
$locNotes = $_POST['loc_note']    ?? [];
$locAddrs = $_POST['loc_address'] ?? [];
$locMaps  = $_POST['loc_map']     ?? [];

foreach ($locNames as $i => $locName) {
    $locName = trim($locName);
    $locAddr = trim($locAddrs[$i] ?? '');
    if ($locName === '' || $locAddr === '') continue;

    $locNote = trim($locNotes[$i] ?? '');
    $locMap  = trim($locMaps[$i] ?? '');

    $locStmt = $conn->prepare('INSERT IGNORE INTO item_locations (item_id, location_name, address, map_url, availability_note) VALUES (?, ?, ?, ?, ?)');
    $locStmt->bind_param('issss', $newItemId, $locName, $locAddr, $locMap, $locNote);
    $locStmt->execute();
    $locStmt->close();
}

$conn->close();
header('Location: /admin');
exit;
