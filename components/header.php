<?php
// Resolve logo path relative to calling page location
$depth = substr_count($_SERVER['SCRIPT_NAME'], '/') - 1;
$logoBase = str_repeat('../', max(0, $depth - 1));
$logoPath = $logoBase . '../public/images/logo.png';
?>
<!-- Header & Navigation Component -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #3D8D7A;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="/public/index.php">
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
                    <a class="nav-link active" href="/public/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/index.php#catalog">Items</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/index.php#search-section">Search</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/index.php#contact">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
