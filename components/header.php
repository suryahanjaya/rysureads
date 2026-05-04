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
                    <a href="/logout" data-i18n="header.logout">Logout</a>
                <?php else: ?>
                    <a href="/login" data-i18n="header.login">Login</a>
                    <a href="/register" data-i18n="header.register">Register</a>
                <?php endif; ?>
                <button class="utility-toggle" type="button" data-theme-toggle aria-label="Toggle theme" data-i18n="header.theme">Light</button>
                <button class="utility-toggle" type="button" data-lang-toggle aria-label="Toggle language" data-i18n="header.lang">中文</button>
            </div>
        </div>

        <div class="header-navline">
            <nav class="primary-nav" aria-label="Primary navigation">
                <button class="<?php echo e(trim('nav-tab' . header_nav_class('/', $activePath))); ?>" type="button" data-nav-toggle="nav-home" aria-expanded="false" data-i18n="nav.home">Home</button>
                <button class="<?php echo e(trim('nav-tab' . header_route_active('/products', $activePath))); ?>" type="button" data-nav-toggle="nav-browse" aria-expanded="false" data-i18n="nav.products">Products</button>
                <button class="<?php echo e(trim('nav-tab' . header_nav_class('/search', $activePath))); ?>" type="button" data-nav-toggle="nav-search" aria-expanded="false" data-i18n="nav.search">Search</button>
                <button class="<?php echo e(trim('nav-tab' . header_nav_class('/contact', $activePath))); ?>" type="button" data-nav-toggle="nav-contact" aria-expanded="false" data-i18n="nav.contact">Contact</button>
            </nav>
        </div>
    </div>

    <div class="nav-backdrop" data-nav-backdrop hidden></div>
    <div class="nav-drawer" data-nav-drawer hidden>
        <button class="nav-drawer-close" type="button" data-nav-close aria-label="Close menu">&times;</button>

        <section class="nav-drawer-panel" data-nav-panel="nav-home">
            <p class="nav-panel-label" data-i18n="drawer.home">Home</p>
            <a href="/" class="nav-drawer-title" data-i18n="drawer.brand">RysuReads</a>
            <div class="nav-panel-grid nav-panel-grid-large">
                <a href="/" class="nav-panel-link" data-i18n="drawer.overview">Overview</a>
                <a href="/products" class="nav-panel-link" data-i18n="drawer.featured">Featured selections</a>
                <a href="/search" class="nav-panel-link" data-i18n="drawer.searchTitles">Search titles</a>
            </div>
        </section>

        <section class="nav-drawer-panel" data-nav-panel="nav-browse">
            <p class="nav-panel-label" data-i18n="drawer.products">Products</p>
            <a href="/products" class="nav-drawer-title" data-i18n="drawer.browseCatalog">Browse the catalog</a>
            <div class="nav-panel-grid nav-panel-grid-large">
                <a href="/products" class="nav-panel-link" data-i18n="drawer.allProducts">All products</a>
                <?php if ($categories && $categories->num_rows > 0): ?>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <a href="<?php echo e(category_url($cat['slug'])); ?>" class="nav-panel-link"><?php echo e($cat['name']); ?></a>
                    <?php endwhile; ?>
                <?php endif; ?>
                <?php if ($isAdmin): ?>
                    <a href="/create-item" class="nav-panel-link" data-i18n="drawer.addItem">Add item</a>
                <?php endif; ?>
            </div>
        </section>

        <section class="nav-drawer-panel" data-nav-panel="nav-search">
            <p class="nav-panel-label" data-i18n="drawer.search">Search</p>
            <a href="/search" class="nav-drawer-title" data-i18n="drawer.searchTitles">Search titles</a>
            <div class="nav-panel-grid nav-panel-grid-large">
                <a href="/search" class="nav-panel-link" data-i18n="drawer.searchTitles">Search titles</a>
                <a href="/products?sort=rating_desc" class="nav-panel-link" data-i18n="drawer.topRated">Top rated titles</a>
            </div>
        </section>

        <section class="nav-drawer-panel" data-nav-panel="nav-contact">
            <p class="nav-panel-label" data-i18n="drawer.contact">Contact</p>
            <a href="/contact" class="nav-drawer-title" data-i18n="drawer.contactDetails">Contact details</a>
            <div class="nav-panel-grid nav-panel-grid-large">
                <a href="/contact" class="nav-panel-link" data-i18n="drawer.contactDetails">Contact details</a>
                <a href="/login" class="nav-panel-link" data-i18n="header.login">Login</a>
                <a href="/register" class="nav-panel-link" data-i18n="header.register">Register</a>
                <?php if ($isAdmin): ?>
                    <a href="/create-item" class="nav-panel-link" data-i18n="drawer.addItem">Add item</a>
                <?php endif; ?>
            </div>
        </section>
    </div>
</header>
