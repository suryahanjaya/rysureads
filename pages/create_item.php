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
            <form action="/save-item" method="POST" enctype="multipart/form-data" class="stack-form">
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

                <!-- Upload gambar -->
                <div>
                    <label for="itemImage" class="form-label">Book cover image</label>
                    <div class="upload-zone" id="uploadZone">
                        <input type="file" class="upload-input" id="itemImage" name="image" accept="image/jpeg,image/png,image/webp,image/gif">
                        <div class="upload-prompt" id="uploadPrompt">
                            <div class="upload-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                            <p class="upload-label">Click to upload or drag &amp; drop</p>
                            <p class="upload-hint">JPG, PNG, WebP — max 5 MB</p>
                        </div>
                        <div class="upload-preview" id="uploadPreview" hidden>
                            <img id="uploadPreviewImg" src="" alt="Preview">
                            <button type="button" class="upload-clear" id="uploadClear" aria-label="Remove image">&times;</button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary-action">Save item</button>
            </form>
        </div>
    </div>
</section>

<style>
.upload-zone {
    position: relative;
    border: 2px dashed rgba(122,31,43,0.25);
    border-radius: 18px;
    background: rgba(122,31,43,0.03);
    min-height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    overflow: hidden;
}
.upload-zone:hover,
.upload-zone.drag-over {
    border-color: var(--brand);
    background: rgba(122,31,43,0.07);
}
.upload-input {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
}
.upload-prompt {
    text-align: center;
    padding: 1.5rem;
    pointer-events: none;
}
.upload-icon {
    color: var(--brand);
    margin-bottom: 0.75rem;
}
.upload-label {
    font-weight: 700;
    color: var(--brand-dark);
    margin: 0 0 0.25rem;
}
.upload-hint {
    font-size: 0.82rem;
    color: var(--muted);
    margin: 0;
}
.upload-preview {
    width: 100%;
    height: 220px;
    position: relative;
}
.upload-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 16px;
}
.upload-clear {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    z-index: 3;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    border: 0;
    background: var(--brand);
    color: #fff;
    font-size: 1.2rem;
    line-height: 1;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(122,31,43,0.3);
}
html[data-theme="dark"] .upload-zone {
    background: rgba(255,255,255,0.03);
    border-color: rgba(255,255,255,0.15);
}
html[data-theme="dark"] .upload-zone:hover {
    border-color: var(--brand);
    background: rgba(201,78,90,0.07);
}
</style>
<script>
(function(){
    var zone = document.getElementById('uploadZone');
    var input = document.getElementById('itemImage');
    var prompt = document.getElementById('uploadPrompt');
    var preview = document.getElementById('uploadPreview');
    var previewImg = document.getElementById('uploadPreviewImg');
    var clearBtn = document.getElementById('uploadClear');

    function showPreview(file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            prompt.hidden = true;
            preview.hidden = false;
        };
        reader.readAsDataURL(file);
    }

    input.addEventListener('change', function() {
        if (input.files && input.files[0]) showPreview(input.files[0]);
    });

    clearBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        input.value = '';
        previewImg.src = '';
        preview.hidden = true;
        prompt.hidden = false;
    });

    zone.addEventListener('dragover', function(e) {
        e.preventDefault();
        zone.classList.add('drag-over');
    });
    zone.addEventListener('dragleave', function() {
        zone.classList.remove('drag-over');
    });
    zone.addEventListener('drop', function(e) {
        e.preventDefault();
        zone.classList.remove('drag-over');
        var files = e.dataTransfer.files;
        if (files && files[0]) {
            input.files = files;
            showPreview(files[0]);
        }
    });
})();
</script>

<?php
$conn->close();
include '../components/page_close.php';
