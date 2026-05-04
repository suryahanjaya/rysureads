<?php

require_once '../config/database.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin');
    exit;
}

$itemId      = (int) ($_POST['item_id'] ?? 0);
$name        = trim($_POST['name'] ?? '');
$price       = trim($_POST['price'] ?? '');
$rating      = trim($_POST['rating'] ?? '4.5');
$categoryId  = (int) ($_POST['category_id'] ?? 0);
$descEn      = trim($_POST['description_en'] ?? '');
$descZh      = trim($_POST['description_zh'] ?? '');

if ($itemId === 0 || $name === '' || $price === '' || $categoryId === 0 || $descEn === '') {
    app_flash('error', 'All required fields must be completed.');
    header('Location: /edit-item?id=' . $itemId);
    exit;
}

$ratingVal = max(0, min(5, (float) $rating));
$slug      = slugify($name);

// --- Handle image upload ---
$imageValue   = null;
$replaceImage = false;
$uploadFile   = $_FILES['image'] ?? null;

if ($uploadFile && $uploadFile['error'] === UPLOAD_ERR_OK && $uploadFile['size'] > 0) {
    $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $finfo   = new finfo(FILEINFO_MIME_TYPE);
    $mime    = $finfo->file($uploadFile['tmp_name']);

    if (!in_array($mime, $allowed, true)) {
        app_flash('error', 'Only JPG, PNG, WebP, and GIF images are allowed.');
        header('Location: /edit-item?id=' . $itemId);
        exit;
    }

    if ($uploadFile['size'] > 5 * 1024 * 1024) {
        app_flash('error', 'Image must be smaller than 5 MB.');
        header('Location: /edit-item?id=' . $itemId);
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
        app_flash('error', 'Failed to save the uploaded image.');
        header('Location: /edit-item?id=' . $itemId);
        exit;
    }

    $imageValue   = 'images/' . $safeName;
    $replaceImage = true;
}

// Update item
if ($replaceImage) {
    $stmt = $conn->prepare('UPDATE items SET name=?, slug=?, price=?, rating=?, category_id=?, description=?, description_en=?, image=? WHERE id=?');
    $stmt->bind_param('ssddisssi', $name, $slug, $price, $ratingVal, $categoryId, $descZh, $descEn, $imageValue, $itemId);
} else {
    $stmt = $conn->prepare('UPDATE items SET name=?, slug=?, price=?, rating=?, category_id=?, description=?, description_en=? WHERE id=?');
    $stmt->bind_param('ssddiisi', $name, $slug, $price, $ratingVal, $categoryId, $descZh, $descEn, $itemId);
}

if (!$stmt->execute()) {
    $stmt->close();
    app_flash('error', 'Failed to update item. The title may already exist.');
    header('Location: /edit-item?id=' . $itemId);
    exit;
}
$stmt->close();

// --- Handle store locations ---
$locIds      = $_POST['loc_id']      ?? [];
$locNames    = $_POST['loc_name']    ?? [];
$locNotes    = $_POST['loc_note']    ?? [];
$locAddrs    = $_POST['loc_address'] ?? [];
$locMaps     = $_POST['loc_map']     ?? [];

// Delete all existing locations for this item, then re-insert
$conn->query("DELETE FROM item_locations WHERE item_id = $itemId");

foreach ($locNames as $i => $locName) {
    $locName = trim($locName);
    $locAddr = trim($locAddrs[$i] ?? '');
    if ($locName === '' || $locAddr === '') continue;

    $locNote = trim($locNotes[$i] ?? '');
    $locMap  = trim($locMaps[$i] ?? '');

    $locStmt = $conn->prepare('INSERT INTO item_locations (item_id, location_name, address, map_url, availability_note) VALUES (?, ?, ?, ?, ?)');
    $locStmt->bind_param('issss', $itemId, $locName, $locAddr, $locMap, $locNote);
    $locStmt->execute();
    $locStmt->close();
}

$conn->close();
header('Location: /admin');
exit;
