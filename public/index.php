<?php

require_once '../config/app.php';

if (($_GET['asset'] ?? '') === 'image') {
    send_image_file((string) ($_GET['path'] ?? ''));
}

$path = current_path();

if ($path === '/search') {
    require_once '../pages/search.php';
    exit;
}

if ($path === '/contact') {
    require_once '../pages/contact.php';
    exit;
}

if ($path === '/products') {
    require_once '../pages/items.php';
    exit;
}

if (preg_match('#^/products/category/([^/]+)$#', $path, $matches)) {
    $_GET['category'] = urldecode($matches[1]);
    require_once '../pages/items.php';
    exit;
}

if (preg_match('#^/products/([^/]+)$#', $path, $matches)) {
    $_GET['slug'] = urldecode($matches[1]);
    require_once '../pages/item_details.php';
    exit;
}

if ($path === '/search-items') {
    require_once '../pages/search_items.php';
    exit;
}

if ($path === '/login') {
    require_once '../pages/login.php';
    exit;
}

if ($path === '/register') {
    require_once '../pages/register.php';
    exit;
}

if ($path === '/forgot-password') {
    require_once '../pages/forgot_password.php';
    exit;
}

if ($path === '/reset-password') {
    require_once '../pages/reset_password.php';
    exit;
}

if ($path === '/logout') {
    require_once '../pages/logout.php';
    exit;
}

if ($path === '/create-item') {
    require_once '../pages/create_item.php';
    exit;
}

require_once '../config/database.php';

$pageTitle = 'Home';
$metaDescription = 'RysuReads is a refined reading catalog for discovering books and categories.';
$bodyClass = 'home-page';
include '../components/page_open.php';

$featuredSql = "SELECT items.*, categories.name AS category_name, categories.slug AS category_slug
                FROM items
                JOIN categories ON categories.id = items.category_id
                ORDER BY items.rating DESC, items.created_at DESC
                LIMIT 2";
$featuredItems = $conn->query($featuredSql);
?>

<section class="hero-section">
    <div class="container">
        <div class="hero-grid">
            <div class="hero-copy">
                <span class="eyebrow" data-i18n="home.eyebrow">Online bookstore</span>
                <h1 data-i18n="home.title">Welcome to RysuReads.</h1>
                <p data-i18n="home.copy">Browse books, categories, and store locations in a clean, timeless online bookstore experience.</p>
                <div class="hero-actions">
                    <a href="/products" class="btn-primary-action" data-i18n="home.browse">Browse the catalog</a>
                    <a href="/search" class="btn-secondary-action" data-i18n="home.searchBtn">Search titles</a>
                </div>
            </div>
            <aside class="hero-panel" aria-label="Highlights">
                <div class="hero-panel-card">
                    <span class="panel-label" data-i18n="home.featuredLabel">Featured paths</span>
                    <strong data-i18n="home.featuredTitle">Books for every reading mood</strong>
                    <p data-i18n="home.featuredCopy">Explore categories, item details, and store availability in one place.</p>
                </div>
                <div class="hero-panel-card hero-panel-card-accent">
                    <span class="panel-label" data-i18n="home.readLabel">Read with ease</span>
                    <p data-i18n="home.readCopy">Balanced spacing and classic typography keep the catalog easy to scan on any screen.</p>
                </div>
            </aside>
        </div>
    </div>
</section>

<section class="section-block" id="catalog">
    <div class="container">
        <div class="section-heading">
            <h2 data-i18n="home.featuredHeading">Featured selections</h2>
        </div>
        <div class="featured-showcase">
            <?php if ($featuredItems && $featuredItems->num_rows > 0): ?>
                <?php while ($item = $featuredItems->fetch_assoc()): ?>
                    <article class="featured-card">
                        <div class="featured-card-image">
                            <img src="<?php echo e(image_url($item['image'] ?: 'images/logo.png')); ?>" alt="<?php echo e($item['name']); ?>">
                        </div>
                        <div class="featured-card-body">
                            <span class="featured-card-label"><?php echo e($item['category_name']); ?></span>
                            <h3 class="featured-card-title"><?php echo e($item['name']); ?></h3>
                            <p class="featured-card-copy"><?php echo e(mb_strimwidth($item['description'], 0, 120, '...')); ?></p>
                            <a href="<?php echo e(product_url($item['slug'])); ?>" class="featured-card-btn" aria-label="View <?php echo e($item['name']); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
$conn->close();
include '../components/page_close.php';
