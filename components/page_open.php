<?php

require_once __DIR__ . '/../config/app.php';

$pageTitle = $pageTitle ?? 'RysuReads';
$metaDescription = $metaDescription ?? 'RysuReads online catalog for books and items.';
$bodyClass = $bodyClass ?? '';
$extraHead = $extraHead ?? '';
?>
<!DOCTYPE html>
<html lang="en" data-theme="light" data-lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo e($metaDescription); ?>">
    <title><?php echo e($pageTitle); ?> | RysuReads</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style><?php echo file_get_contents(__DIR__ . '/../public/css/style.css'); ?></style>
    <?php echo $extraHead; ?>
</head>
<body class="<?php echo e($bodyClass); ?>">
<?php include __DIR__ . '/header.php'; ?>
<main class="main-content">
