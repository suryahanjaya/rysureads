<?php

$logoPath = image_url('images/logo.png');
$activePath = current_path();
$user = current_user();
$isAdmin = is_admin();
$categories = null;
if (isset($conn) && $conn instanceof mysqli) {
    $categories = $conn->query("SELECT name, slug FROM categories ORDER BY name");
}

function header_nav_class(string $route, string $currentPath): string
{
    return $route === $currentPath ? ' is-active' : '';
}

function header_route_active(string $prefix, string $currentPath): string
{
    return str_starts_with($currentPath, $prefix) ? ' is-active' : '';
}
?>
<header class="site-header sticky-top">
    <div class="header-shell container">
        <div class="header-topline">
            <a class="brand-lockup" href="/" aria-label="RysuReads home">
                <img src="<?php echo e($logoPath); ?>" alt="RysuReads logo" class="brand-mark">
                <span class="brand-wordmark">
                    <span class="brand-name">RysuReads</span>
                    <span class="brand-tagline">Read More. Grow More.</span>
                </span>
            </a>

            <div class="header-utility">
                <?php if ($user): ?>
                    <span class="header-user">Hi, <?php echo e($user['name']); ?></span>
                    <a href="/logout">Logout</a>
                <?php else: ?>
                    <a href="/login">Login</a>
                    <a href="/register">Register</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="header-navline">
            <nav class="primary-nav" aria-label="Primary navigation">
                <button class="<?php echo e(trim('nav-tab' . header_nav_class('/', $activePath))); ?>" type="button" data-nav-toggle="nav-home" aria-expanded="false">Home</button>
                <button class="<?php echo e(trim('nav-tab' . header_route_active('/products', $activePath))); ?>" type="button" data-nav-toggle="nav-browse" aria-expanded="false">Products</button>
                <button class="<?php echo e(trim('nav-tab' . header_nav_class('/search', $activePath))); ?>" type="button" data-nav-toggle="nav-search" aria-expanded="false">Search</button>
                <button class="<?php echo e(trim('nav-tab' . header_nav_class('/contact', $activePath))); ?>" type="button" data-nav-toggle="nav-contact" aria-expanded="false">Contact</button>
            </nav>
        </div>
    </div>

    <div class="nav-backdrop" data-nav-backdrop hidden></div>
    <div class="nav-drawer" data-nav-drawer hidden>
        <button class="nav-drawer-close" type="button" data-nav-close aria-label="Close menu">&times;</button>

        <section class="nav-drawer-panel" data-nav-panel="nav-home">
            <p class="nav-panel-label">Home</p>
            <a href="/" class="nav-drawer-title">RysuReads</a>
            <div class="nav-panel-grid nav-panel-grid-large">
                <a href="/" class="nav-panel-link">Overview</a>
                <a href="/products" class="nav-panel-link">Featured selections</a>
                <a href="/search" class="nav-panel-link">Search titles</a>
            </div>
        </section>

        <section class="nav-drawer-panel" data-nav-panel="nav-browse">
            <p class="nav-panel-label">Products</p>
            <a href="/products" class="nav-drawer-title">Browse the catalog</a>
            <div class="nav-panel-grid nav-panel-grid-large">
                <a href="/products" class="nav-panel-link">All products</a>
                <?php if ($categories && $categories->num_rows > 0): ?>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <a href="<?php echo e(category_url($cat['slug'])); ?>" class="nav-panel-link"><?php echo e($cat['name']); ?></a>
                    <?php endwhile; ?>
                <?php endif; ?>
                <?php if ($isAdmin): ?>
                    <a href="/create-item" class="nav-panel-link">Add item</a>
                <?php endif; ?>
            </div>
        </section>

        <section class="nav-drawer-panel" data-nav-panel="nav-search">
            <p class="nav-panel-label">Search</p>
            <a href="/search" class="nav-drawer-title">Search titles</a>
            <div class="nav-panel-grid nav-panel-grid-large">
                <a href="/search" class="nav-panel-link">Search titles</a>
                <a href="/products?sort=rating_desc" class="nav-panel-link">Top rated titles</a>
            </div>
        </section>

        <section class="nav-drawer-panel" data-nav-panel="nav-contact">
            <p class="nav-panel-label">Contact</p>
            <a href="/contact" class="nav-drawer-title">Contact details</a>
            <div class="nav-panel-grid nav-panel-grid-large">
                <a href="/contact" class="nav-panel-link">Contact details</a>
                <a href="/login" class="nav-panel-link">Login</a>
                <a href="/register" class="nav-panel-link">Register</a>
                <?php if ($isAdmin): ?>
                    <a href="/create-item" class="nav-panel-link">Add item</a>
                <?php endif; ?>
            </div>
        </section>
    </div>
</header>
