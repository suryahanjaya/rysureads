<?php

require_once '../config/database.php';

$slug = trim($_GET['slug'] ?? '');
$itemId = (int) ($_GET['id'] ?? 0);

$pageTitle = 'Item Details';
$metaDescription = 'View item details, category path, and store availability.';
$bodyClass = 'details-page';
include '../components/page_open.php';

$sql = "SELECT items.*, categories.name AS category_name, categories.slug AS category_slug
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
                <p><?php echo e($item['description']); ?></p>
                <div class="detail-actions">
                    <a href="<?php echo e(category_url($item['category_slug'])); ?>" class="btn-secondary-action">Back to category</a>
                    <a href="/products" class="btn-primary-action">Browse more titles</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-block">
    <div class="container">
        <div class="section-heading">
            <span>Store locations</span>
            <h2>Where this item is available</h2>
        </div>
        <div class="row g-3">
            <?php if ($locations && $locations->num_rows > 0): ?>
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
            <?php else: ?>
                <div class="col-12">
                    <div class="empty-state">No location data has been added for this item yet.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

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

<?php
$locationsStmt->close();
$relatedStmt->close();
$conn->close();
include '../components/page_close.php';
