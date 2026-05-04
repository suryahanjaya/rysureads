<?php

require_once '../config/database.php';

$keyword = trim($_GET['q'] ?? '');
$sortKey = $_GET['sort'] ?? 'rating_desc';
$sortOptions = allowed_sort_options();
$sortSql = $sortOptions[$sortKey] ?? $sortOptions['rating_desc'];

function search_preview_text(?string $english, string $fallback, string $emptyMessage): string
{
    $english = trim((string) $english);
    if ($english !== '') {
        return $english;
    }

    if ($fallback !== '' && preg_match('/\p{Han}/u', $fallback) !== 1) {
        return $fallback;
    }

    return $emptyMessage;
}

$sql = "SELECT items.*, categories.name AS category_name, categories.slug AS category_slug
        FROM items
        JOIN categories ON categories.id = items.category_id
        WHERE items.name LIKE ? OR items.description LIKE ? OR categories.name LIKE ?
        ORDER BY $sortSql
        LIMIT 12";

$stmt = $conn->prepare($sql);
$like = '%' . $keyword . '%';
$stmt->bind_param('sss', $like, $like, $like);
$stmt->execute();
$results = $stmt->get_result();

if ($results->num_rows === 0) {
    echo '<div class="empty-state">No matching items found.</div>';
    $stmt->close();
    $conn->close();
    exit;
}

while ($item = $results->fetch_assoc()): ?>
    <article class="search-result-card">
        <img src="<?php echo e(image_url($item['image'] ?: 'images/logo.png')); ?>" alt="<?php echo e($item['name']); ?>" class="search-result-image">
        <div>
            <div class="product-meta">
                <span class="chip"><?php echo e($item['category_name']); ?></span>
                <span class="rating">&#9733; <?php echo number_format((float) $item['rating'], 1); ?></span>
            </div>
            <h3><?php echo e($item['name']); ?></h3>
            <p>
                <span class="item-desc-en"><?php echo e(mb_strimwidth(search_preview_text($item['description_en'] ?? null, (string) $item['description'], 'Description unavailable in English.'), 0, 110, '...')); ?></span>
                <span class="item-desc-zh" hidden><?php echo e(mb_strimwidth((string) $item['description'], 0, 110, '...')); ?></span>
            </p>
            <div class="product-bottom">
                <strong>$<?php echo number_format((float) $item['price'], 2); ?></strong>
                <a href="<?php echo e(product_url($item['slug'])); ?>" class="btn-link-action">View details</a>
            </div>
        </div>
    </article>
<?php endwhile;

$stmt->close();
$conn->close();
