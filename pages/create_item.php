<?php
/*
 * create_item.php — Item Submission Form
 */
require_once '../config/database.php';

$pageTitle = 'Add New Book';
$cssDepth  = '../';
$jsDepth   = '../';
include '../components/page_open.php';

// Fetch categories for the dropdown
$cats = $conn->query("SELECT id, name FROM categories ORDER BY name");

// Flash message passed from save_item.php
$error = $_GET['error'] ?? '';
?>

<section class="py-5">
    <div class="container">

        <a href="items.php" class="btn-back">< Back to Catalog</a>
        <h2 class="section-title">Add New Book</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger text-center">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="contact-section"><!-- reuse existing form card style -->
                    <form action="save_item.php" method="POST">

                        <div class="mb-3">
                            <label for="itemName" class="form-label">Book Title</label>
                            <input type="text" class="form-control" id="itemName"
                                   name="name" placeholder="e.g. The Great Gatsby" required>
                        </div>

                        <div class="mb-3">
                            <label for="itemPrice" class="form-label">Price (USD)</label>
                            <input type="number" class="form-control" id="itemPrice"
                                   name="price" placeholder="e.g. 12.99"
                                   min="0" step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label for="itemCategory" class="form-label">Category</label>
                            <select class="form-control" id="itemCategory" name="category_id" required>
                                <option value="" disabled selected>— Select category —</option>
                                <?php while ($cat = $cats->fetch_assoc()): ?>
                                    <option value="<?php echo $cat['id']; ?>">
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="itemDesc" class="form-label">Description</label>
                            <textarea class="form-control" id="itemDesc" name="description"
                                      rows="4" placeholder="Short description of the book..." required></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn-submit"
                                    onclick="showMessage('Saving book...')">
                                Save Book
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
</section>

<?php
$conn->close();
include '../components/page_close.php';
?>
