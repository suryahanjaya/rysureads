<?php

require_once '../config/database.php';

$pageTitle = 'Products';
$metaDescription = 'Browse the full RysuReads catalog with sorting, pagination, and category filters.';
$bodyClass = 'catalog-page';
include '../components/page_open.php';

$sortKey = $_GET['sort'] ?? 'newest';
$sortOptions = allowed_sort_options();
$sortSql = $sortOptions[$sortKey] ?? $sortOptions['newest'];
$categorySlug = $_GET['category'] ?? '';
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 6;
$offset = ($page - 1) * $perPage;

$categories = $conn->query("SELECT id, name, slug FROM categories ORDER BY name");

if ($categorySlug !== '') {
    $countStmt = $conn->prepare('SELECT COUNT(*) AS total FROM items INNER JOIN categories ON categories.id = items.category_id WHERE categories.slug = ?');
    $countStmt->bind_param('s', $categorySlug);
    $countStmt->execute();
    $totalRows = (int) ($countStmt->get_result()->fetch_assoc()['total'] ?? 0);
    $countStmt->close();

    $sql = "SELECT items.*, categories.name AS category_name, categories.slug AS category_slug
            FROM items
            INNER JOIN categories ON categories.id = items.category_id
            WHERE categories.slug = ?
            ORDER BY $sortSql
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sii', $categorySlug, $perPage, $offset);
} else {
    $countStmt = $conn->prepare('SELECT COUNT(*) AS total FROM items');
    $countStmt->execute();
    $totalRows = (int) ($countStmt->get_result()->fetch_assoc()['total'] ?? 0);
    $countStmt->close();

    $sql = "SELECT items.*, categories.name AS category_name, categories.slug AS category_slug
            FROM items
            INNER JOIN categories ON categories.id = items.category_id
            ORDER BY $sortSql
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $perPage, $offset);
}

$stmt->execute();
$items = $stmt->get_result();
$totalPages = max(1, (int) ceil($totalRows / $perPage));
?>

<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb custom-breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
        </nav>
        <div class="page-heading-row">
            <div>
                <h1>Browse the full catalog</h1>
                <p>Sort by name, price, or rating, then open any item to view details and store availability.</p>
            </div>
            <?php if (is_admin()): ?>
                <a href="/create-item" class="btn-primary-action">Add item</a>
            <?php endif; ?>
        </div>
        <form class="catalog-toolbar" method="get">
            <div class="toolbar-group">
                <label for="categoryFilter">Category</label>
                <select id="categoryFilter" name="category" class="form-select" data-category-filter>
                    <option value="">All categories</option>
                    <?php if ($categories): ?>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <option value="<?php echo e($cat['slug']); ?>" <?php echo $categorySlug === $cat['slug'] ? 'selected' : ''; ?>>
                                <?php echo e($cat['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="toolbar-group">
                <label for="sortFilter">Sort by</label>
                <select id="sortFilter" name="sort" class="form-select" data-sort-filter>
                    <option value="newest" <?php echo $sortKey === 'newest' ? 'selected' : ''; ?>>Newest</option>
                    <option value="name_asc" <?php echo $sortKey === 'name_asc' ? 'selected' : ''; ?>>Name A-Z</option>
                    <option value="name_desc" <?php echo $sortKey === 'name_desc' ? 'selected' : ''; ?>>Name Z-A</option>
                    <option value="price_asc" <?php echo $sortKey === 'price_asc' ? 'selected' : ''; ?>>Price low-high</option>
                    <option value="price_desc" <?php echo $sortKey === 'price_desc' ? 'selected' : ''; ?>>Price high-low</option>
                    <option value="rating_desc" <?php echo $sortKey === 'rating_desc' ? 'selected' : ''; ?>>Top rated</option>
                </select>
            </div>
        </form>
    </div>
</section>

<section class="section-block pt-0">
    <div class="container">
        <div class="row g-4">
            <?php if ($items && $items->num_rows > 0): ?>
                <?php while ($item = $items->fetch_assoc()): ?>
                    <div class="col-sm-6 col-lg-4">
                        <article class="product-card">
                            <img src="<?php echo e(image_url($item['image'] ?: 'images/logo.png')); ?>" alt="<?php echo e($item['name']); ?>" class="product-image">
                            <div class="product-card-body">
                                <div class="product-meta">
                                    <a class="chip" href="<?php echo e(category_url($item['category_slug'])); ?>"><?php echo e($item['category_name']); ?></a>
                                    <span class="rating">&#9733; <?php echo number_format((float) $item['rating'], 1); ?></span>
                                </div>
                                <h3><?php echo e($item['name']); ?></h3>
                                <p><?php echo e(mb_strimwidth($item['description'], 0, 96, '...')); ?></p>
                                <div class="product-bottom">
                                    <strong>$<?php echo number_format((float) $item['price'], 2); ?></strong>
                                    <a href="<?php echo e(product_url($item['slug'])); ?>" class="btn-link-action">Details</a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="empty-state">No items matched the current filter.</div>
                </div>
            <?php endif; ?>
        </div>

        <nav class="pagination-shell" aria-label="Products pagination">
            <?php if ($page > 1): ?>
                <a class="pagination-link" href="?<?php echo e(http_build_query(array_filter(['page' => $page - 1, 'sort' => $sortKey, 'category' => $categorySlug]))); ?>">Previous</a>
            <?php endif; ?>
            <span class="pagination-status">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
            <?php if ($page < $totalPages): ?>
                <a class="pagination-link" href="?<?php echo e(http_build_query(array_filter(['page' => $page + 1, 'sort' => $sortKey, 'category' => $categorySlug]))); ?>">Next</a>
            <?php endif; ?>
        </nav>
    </div>
</section>

<?php
$stmt->close();
$conn->close();
include '../components/page_close.php';
