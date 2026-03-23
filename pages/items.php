<?php
/*
 * items.php — Dynamic Item Catalog (from database)
 */
require_once '../config/database.php';

$pageTitle = 'Book Catalog';
$cssDepth  = '../';
$jsDepth   = '../';
include '../components/page_open.php';

// Fetch all items with their category name
$sql    = "SELECT items.*, categories.name AS category_name
           FROM items
           LEFT JOIN categories ON items.category_id = categories.id
           ORDER BY items.created_at DESC";
$result = $conn->query($sql);
?>

<section class="py-5" id="catalog">
    <div class="container">

        <h2 class="section-title">Book Catalog</h2>

        <div class="text-center mb-4">
            <a href="create_item.php" class="btn-view">+ Add New Book</a>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
            <div class="row">
                <?php while ($item = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4 book-card-wrapper">
                        <div class="card book-card">
                            <?php if (!empty($item['image'])): ?>
                                <img src="<?php echo htmlspecialchars($item['image']); ?>"
                                     class="card-img-top" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <?php else: ?>
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                     style="height:240px; color:#aaa; font-size:0.9rem;">
                                    No Image
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <span class="detail-category">
                                    <?php echo htmlspecialchars($item['category_name'] ?? 'Uncategorized'); ?>
                                </span>
                                <h5 class="card-title mt-1"><?php echo htmlspecialchars($item['name']); ?></h5>
                                <p class="card-text">
                                    <?php echo htmlspecialchars(mb_strimwidth($item['description'], 0, 90, '...')); ?>
                                </p>
                                <div class="book-price">$<?php echo number_format($item['price'], 2); ?></div>
                                <a href="item_details.php?id=<?php echo $item['id']; ?>" class="btn-view">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

        <?php else: ?>
            <div class="text-center py-5" style="color:#888;">
                <p>No books yet. <a href="create_item.php" style="color:#3D8D7A;">Add the first one!</a></p>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php
$conn->close();
include '../components/page_close.php';
?>
