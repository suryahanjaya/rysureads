<?php
// Determine if we are in the 'pages' folder or 'public' folder
$isInPages  = (strpos($_SERVER['SCRIPT_NAME'], '/pages/') !== false);
$isInPublic = (strpos($_SERVER['SCRIPT_NAME'], '/public/') !== false);

// Logic:
// From public/index.php -> logo is in images/logo.png, items is in ../pages/items.php
// From pages/items.php  -> logo is in ../public/images/logo.png, home is in ../public/index.php

$logoPath   = $isInPages ? '../public/images/logo.png' : 'images/logo.png';
$homeLink   = $isInPages ? '../public/index.php'       : 'index.php';
$itemsLink  = $isInPages ? 'items.php'                 : '../pages/items.php';
$catalogLink = $isInPages ? '../public/index.php#catalog'        : 'index.php#catalog';
$searchLink  = $isInPages ? '../public/index.php#search-section' : 'index.php#search-section';
$contactLink = $isInPages ? '../public/index.php#contact'        : 'index.php#contact';
?>
<!-- Header & Navigation Component -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #3D8D7A;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="<?php echo $homeLink; ?>">
            <img src="<?php echo $logoPath; ?>" alt="RysuReads Logo" height="36" style="object-fit: contain;">
            RysuReads
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $homeLink; ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $catalogLink; ?>">Items</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $itemsLink; ?>">DB Catalog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $searchLink; ?>">Search</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $contactLink; ?>">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
