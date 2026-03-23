<?php
/*
 * Reusable page shell — open section.
 * Usage: include this BEFORE any page content.
 *
 * Variables to set before including:
 *   $pageTitle (string)  — shown in <title>
 *   $cssDepth  (string)  — relative path prefix, e.g. '../' for pages/
 */
$pageTitle = $pageTitle ?? 'RysuReads';
$cssDepth  = $cssDepth  ?? '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - RysuReads</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $cssDepth; ?>public/css/style.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<div class="main-content">
