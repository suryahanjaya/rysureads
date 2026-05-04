<?php

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: create_item.php');
    exit;
}

require_admin();

$name = trim($_POST['name'] ?? '');
$price = trim($_POST['price'] ?? '');
$categoryId = (int) ($_POST['category_id'] ?? 0);
$description = trim($_POST['description'] ?? '');
$image = trim($_POST['image'] ?? '');

if ($name === '' || $price === '' || $categoryId === 0 || $description === '') {
    app_flash('error', 'All required fields must be completed.');
    header('Location: create_item.php');
    exit;
}

$slug = slugify($name);
$rating = 4.5;
$imageValue = $image !== '' ? $image : null;

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
