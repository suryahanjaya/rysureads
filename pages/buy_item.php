<?php

require_once '../config/database.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /products');
    exit;
}

$itemId = (int) ($_POST['item_id'] ?? 0);
$user   = current_user();
$userId = (int) ($user['id'] ?? 0);

if ($itemId === 0 || $userId === 0) {
    header('Location: /products');
    exit;
}

// Verify item exists
$chk = $conn->prepare('SELECT id FROM items WHERE id = ? LIMIT 1');
$chk->bind_param('i', $itemId);
$chk->execute();
$exists = $chk->get_result()->fetch_assoc();
$chk->close();

if (!$exists) {
    header('Location: /products');
    exit;
}

// Insert purchase — IGNORE silently handles duplicates
$stmt = $conn->prepare('INSERT IGNORE INTO purchases (user_id, item_id) VALUES (?, ?)');
$stmt->bind_param('ii', $userId, $itemId);
$stmt->execute();
$stmt->close();
$conn->close();

header('Location: /my-books');
exit;
