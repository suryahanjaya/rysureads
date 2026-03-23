<?php
/*
 * save_item.php — Inserts a new book into the database.
 * No HTML output — processes the form then redirects.
 */
require_once '../config/database.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: create_item.php');
    exit;
}

// Sanitise inputs
$name        = trim($_POST['name']        ?? '');
$price       = trim($_POST['price']       ?? '');
$category_id = (int) ($_POST['category_id'] ?? 0);
$description = trim($_POST['description'] ?? '');

// Basic validation
if ($name === '' || $price === '' || $category_id === 0 || $description === '') {
    header('Location: create_item.php?error=All+fields+are+required.');
    exit;
}

// Prepared statement — INSERT INTO items
$stmt = $conn->prepare(
    "INSERT INTO items (name, price, category_id, description) VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("sdis", $name, $price, $category_id, $description);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header('Location: items.php');
    exit;
} else {
    $stmt->close();
    $conn->close();
    header('Location: create_item.php?error=Database+error.+Please+try+again.');
    exit;
}
