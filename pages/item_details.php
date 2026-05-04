<?php

require_once '../config/database.php';

$slug   = trim($_GET['slug'] ?? '');
$itemId = (int) ($_GET['id'] ?? 0);

$pageTitle       = 'Item Details';
$metaDescription = 'View item details, category path, and store availability.';
$bodyClass       = 'details-page';
include '../components/page_open.php';

$sql  = "SELECT items.*, categories.name AS category_name, categories.slug AS category_slug
         FROM items
         JOIN categories ON categories.id = items.category_id
         WHERE items.slug = ? OR items.id = ?
         LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $slug, $itemId);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$item) {
    echo '<section class="section-block"><div class="container"><div class="empty-state">Item not found.</div></div></section>';
    include '../components/page_close.php';
    exit;
}

// Check if already purchased
$alreadyPurchased = false;
$loggedUser = current_user();
if ($loggedUser) {
    $chk = $conn->prepare('SELECT id FROM purchases WHERE user_id = ? AND item_id = ? LIMIT 1');
    $chk->bind_param('ii', $loggedUser['id'], $item['id']);
    $chk->execute();
    $alreadyPurchased = (bool) $chk->get_result()->fetch_assoc();
    $chk->close();
}

$locationsStmt = $conn->prepare('SELECT location_name, address, map_url, availability_note FROM item_locations WHERE item_id = ? ORDER BY location_name');
$locationsStmt->bind_param('i', $item['id']);
$locationsStmt->execute();
$locations = $locationsStmt->get_result();

$relatedStmt = $conn->prepare('SELECT name, slug, price, image FROM items WHERE category_id = ? AND id <> ? ORDER BY rating DESC LIMIT 3');
$relatedStmt->bind_param('ii', $item['category_id'], $item['id']);
$relatedStmt->execute();
$related = $relatedStmt->get_result();
?>

<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb custom-breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/products">Products</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(category_url($item['category_slug'])); ?>"><?php echo e($item['category_name']); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo e($item['name']); ?></li>
            </ol>
        </nav>
    </div>
</section>

<section class="section-block pt-0">
    <div class="container">
        <div class="detail-layout">
            <div class="detail-media">
                <img src="<?php echo e(image_url($item['image'] ?: 'images/logo.png')); ?>" alt="<?php echo e($item['name']); ?>" class="detail-image">
            </div>
            <div class="detail-copy">
                <span class="chip"><?php echo e($item['category_name']); ?></span>
                <h1><?php echo e($item['name']); ?></h1>
                <p class="rating-line">Rating: <?php echo number_format((float) $item['rating'], 1); ?> / 5</p>
                <p class="price-line">$<?php echo number_format((float) $item['price'], 2); ?></p>

                <!-- Dual-language description: JS toggles visibility -->
                <p class="item-desc-en"><?php echo e($item['description_en'] ?: $item['description']); ?></p>
                <p class="item-desc-zh" hidden><?php echo e($item['description']); ?></p>

                <div class="detail-actions">
                    <?php if ($loggedUser): ?>
                        <?php if ($alreadyPurchased): ?>
                            <span class="buy-badge buy-badge-owned">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                Already purchased
                            </span>
                            <a href="/my-books" class="btn-secondary-action">My Books</a>
                        <?php else: ?>
                            <form method="POST" action="/buy-item" style="display:inline">
                                <input type="hidden" name="item_id" value="<?php echo (int) $item['id']; ?>">
                                <button type="submit" class="btn-primary-action buy-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    Buy — $<?php echo number_format((float) $item['price'], 2); ?>
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="/login" class="btn-primary-action">Login to Buy</a>
                    <?php endif; ?>
                    <a href="/products" class="btn-secondary-action">Browse more</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if ($locations && $locations->num_rows > 0): ?>
<section class="section-block">
    <div class="container">
        <div class="section-heading">
            <span>Store locations</span>
            <h2>Where this item is available</h2>
        </div>
        <div class="row g-3">
            <?php while ($location = $locations->fetch_assoc()): ?>
                <div class="col-md-6">
                    <article class="location-card">
                        <h3><?php echo e($location['location_name']); ?></h3>
                        <p><?php echo e($location['address']); ?></p>
                        <p class="muted-line"><?php echo e($location['availability_note'] ?: 'Available'); ?></p>
                        <a href="<?php echo e($location['map_url']); ?>" target="_blank" rel="noopener" class="btn-link-action">Open Google Maps</a>
                    </article>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($related && $related->num_rows > 0): ?>
<section class="section-block">
    <div class="container">
        <div class="section-heading">
            <span>Related items</span>
            <h2>Same category, nearby choices</h2>
        </div>
        <div class="row g-4">
            <?php while ($row = $related->fetch_assoc()): ?>
                <div class="col-sm-6 col-lg-4">
                    <article class="product-card">
                        <img src="<?php echo e(image_url($row['image'] ?: 'images/logo.png')); ?>" alt="<?php echo e($row['name']); ?>" class="product-image">
                        <div class="product-card-body">
                            <h3><?php echo e($row['name']); ?></h3>
                            <div class="product-bottom">
                                <strong>$<?php echo number_format((float) $row['price'], 2); ?></strong>
                                <a href="<?php echo e(product_url($row['slug'])); ?>" class="btn-link-action">View</a>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
/* Switch description language based on stored lang preference */
(function() {
    function syncDesc() {
        var lang = localStorage.getItem('rysureads-lang') || 'en';
        var en = document.querySelectorAll('.item-desc-en');
        var zh = document.querySelectorAll('.item-desc-zh');
        en.forEach(function(el) { el.hidden = lang === 'zh'; });
        zh.forEach(function(el) { el.hidden = lang !== 'zh'; });
    }
    syncDesc();
    /* Re-sync when lang toggle fires */
    document.addEventListener('click', function(e) {
        if (e.target && e.target.hasAttribute('data-lang-toggle')) {
            setTimeout(syncDesc, 50);
        }
    });
})();
</script>

<?php
$locationsStmt->close();
$relatedStmt->close();
$conn->close();
include '../components/page_close.php';
