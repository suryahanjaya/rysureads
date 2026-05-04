<?php

require_once '../config/database.php';

require_admin();

$pageTitle = 'Add Item';
$metaDescription = 'Create a new catalog item in the RysuReads database.';
$cssDepth = '../';
$jsDepth = '../';
$bodyClass = 'form-page';
include '../components/page_open.php';

$cats = $conn->query("SELECT id, name FROM categories ORDER BY name");
$error = app_flash('error');
?>

<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb custom-breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/products">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Item</li>
            </ol>
        </nav>
        <div class="page-heading-row">
            <div>
                <h1>Add a catalog item</h1>
                <p>Add a new title to the catalog and assign it to a category.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-block pt-0">
    <div class="container">
        <div class="form-shell">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo e($error); ?></div>
            <?php endif; ?>
            <form action="save_item.php" method="POST" class="stack-form">
                <div>
                    <label for="itemName" class="form-label">Item name</label>
                    <input type="text" class="form-control" id="itemName" name="name" required maxlength="150">
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="itemPrice" class="form-label">Price</label>
                        <input type="number" class="form-control" id="itemPrice" name="price" min="0" step="0.01" required>
                    </div>
                    <div class="col-md-6">
                        <label for="itemCategory" class="form-label">Category</label>
                        <select class="form-select" id="itemCategory" name="category_id" required>
                            <option value="">Select a category</option>
                            <?php while ($cat = $cats->fetch_assoc()): ?>
                                <option value="<?php echo (int) $cat['id']; ?>"><?php echo e($cat['name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="itemDesc" class="form-label">Description</label>
                    <textarea class="form-control" id="itemDesc" name="description" rows="5" required></textarea>
                </div>
                <div>
                    <label for="itemImage" class="form-label">Image path</label>
                    <input type="text" class="form-control" id="itemImage" name="image" placeholder="images/item-new.jpg">
                </div>
                <button type="submit" class="btn-primary-action">Save item</button>
            </form>
        </div>
    </div>
</section>

<?php
$conn->close();
include '../components/page_close.php';
