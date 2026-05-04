<?php

require_once '../config/database.php';
require_admin();

$pageTitle = 'Add Item';
$metaDescription = 'Create a new catalog item in the RysuReads database.';
$cssDepth = '../';
$jsDepth  = '../';
$bodyClass = 'form-page';
include '../components/page_open.php';

$cats  = $conn->query("SELECT id, name FROM categories ORDER BY name");
$error = app_flash('error');
?>

<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb custom-breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Item</li>
            </ol>
        </nav>
        <div class="page-heading-row">
            <div>
                <h1>Add a new book</h1>
                <p>Fill in all details including descriptions, rating, image, and store locations.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-block pt-0">
    <div class="container">
        <div class="form-shell" style="max-width:760px">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo e($error); ?></div>
            <?php endif; ?>

            <form action="/save-item" method="POST" enctype="multipart/form-data" class="stack-form">

                <!-- Name -->
                <div>
                    <label for="itemName" class="form-label">Item name</label>
                    <input type="text" class="form-control" id="itemName" name="name" required maxlength="150">
                </div>

                <!-- Price + Rating -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="itemPrice" class="form-label">Price ($)</label>
                        <input type="number" class="form-control" id="itemPrice" name="price" min="0" step="0.01" required>
                    </div>
                    <div class="col-md-6">
                        <label for="itemRating" class="form-label">Rating (0 – 5)</label>
                        <input type="number" class="form-control" id="itemRating" name="rating" value="4.5" min="0" max="5" step="0.1" required>
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label for="itemCategory" class="form-label">Category</label>
                    <select class="form-select" id="itemCategory" name="category_id" required>
                        <option value="">Select a category</option>
                        <?php while ($cat = $cats->fetch_assoc()): ?>
                            <option value="<?php echo (int) $cat['id']; ?>"><?php echo e($cat['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Description EN -->
                <div>
                    <label for="itemDescEn" class="form-label">Description (English)</label>
                    <textarea class="form-control" id="itemDescEn" name="description_en" rows="4" required></textarea>
                </div>

                <!-- Description ZH -->
                <div>
                    <label for="itemDescZh" class="form-label">Description (Mandarin 中文)</label>
                    <textarea class="form-control" id="itemDescZh" name="description_zh" rows="4"></textarea>
                </div>

                <!-- Image upload -->
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

                <!-- Store Locations -->
                <div>
                    <label class="form-label">Store Locations</label>
                    <div id="locationsContainer"></div>
                    <button type="button" class="add-location-btn" id="addLocationBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add location
                    </button>
                </div>

                <button type="submit" class="btn-primary-action">Save Book</button>
            </form>
        </div>
    </div>
</section>

<style>
.location-entry { border:1px solid var(--border); border-radius:14px; padding:1rem; margin-bottom:0.75rem; background:rgba(122,31,43,0.02); }
.location-entry-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.65rem; }
.location-entry-num { font-weight:700; font-size:0.82rem; text-transform:uppercase; letter-spacing:0.1em; color:var(--muted); }
.location-remove-btn { background:rgba(220,38,38,0.08); color:#dc2626; border:1px solid rgba(220,38,38,0.18); border-radius:8px; padding:0.25rem 0.7rem; font-size:0.8rem; font-weight:700; cursor:pointer; }
.location-remove-btn:hover { background:rgba(220,38,38,0.16); }
.add-location-btn { display:inline-flex; align-items:center; gap:0.4rem; background:rgba(122,31,43,0.07); color:var(--brand-dark); border:1px dashed rgba(122,31,43,0.3); border-radius:10px; padding:0.5rem 1rem; font-size:0.85rem; font-weight:700; cursor:pointer; transition:background 0.15s; }
.add-location-btn:hover { background:rgba(122,31,43,0.13); }
.upload-zone { position:relative; border:2px dashed rgba(122,31,43,0.25); border-radius:18px; background:rgba(122,31,43,0.03); min-height:160px; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:border-color 0.2s, background 0.2s; overflow:hidden; }
.upload-zone:hover, .upload-zone.drag-over { border-color:var(--brand); background:rgba(122,31,43,0.07); }
.upload-input { position:absolute; inset:0; width:100%; height:100%; opacity:0; cursor:pointer; z-index:2; }
.upload-prompt { text-align:center; padding:1.5rem; pointer-events:none; }
.upload-icon { color:var(--brand); margin-bottom:0.75rem; }
.upload-label { font-weight:700; color:var(--brand-dark); margin:0 0 0.25rem; }
.upload-hint { font-size:0.82rem; color:var(--muted); margin:0; }
.upload-preview { width:100%; height:220px; position:relative; }
.upload-preview img { width:100%; height:100%; object-fit:cover; border-radius:16px; }
.upload-clear { position:absolute; top:0.5rem; right:0.5rem; z-index:3; width:2rem; height:2rem; border-radius:50%; border:0; background:var(--brand); color:#fff; font-size:1.2rem; cursor:pointer; box-shadow:0 4px 10px rgba(122,31,43,0.3); }
html[data-theme="dark"] .upload-zone { background:rgba(255,255,255,0.03); border-color:rgba(255,255,255,0.15); }
html[data-theme="dark"] .upload-zone:hover { border-color:var(--brand); background:rgba(201,78,90,0.07); }
html[data-theme="dark"] .location-entry { background:rgba(255,255,255,0.02); border-color:rgba(255,255,255,0.1); }
html[data-theme="dark"] .add-location-btn { background:rgba(255,255,255,0.05); border-color:rgba(255,255,255,0.18); color:var(--text); }
</style>

<script>
(function(){
    /* Upload preview */
    var zone=document.getElementById('uploadZone'),input=document.getElementById('itemImage'),
        prompt=document.getElementById('uploadPrompt'),preview=document.getElementById('uploadPreview'),
        previewImg=document.getElementById('uploadPreviewImg'),clearBtn=document.getElementById('uploadClear');
    function showPreview(file){var r=new FileReader();r.onload=function(e){previewImg.src=e.target.result;prompt.hidden=true;preview.hidden=false;};r.readAsDataURL(file);}
    input.addEventListener('change',function(){if(input.files&&input.files[0])showPreview(input.files[0]);});
    clearBtn.addEventListener('click',function(e){e.preventDefault();e.stopPropagation();input.value='';previewImg.src='';preview.hidden=true;prompt.hidden=false;});
    zone.addEventListener('dragover',function(e){e.preventDefault();zone.classList.add('drag-over');});
    zone.addEventListener('dragleave',function(){zone.classList.remove('drag-over');});
    zone.addEventListener('drop',function(e){e.preventDefault();zone.classList.remove('drag-over');var f=e.dataTransfer.files;if(f&&f[0]){input.files=f;showPreview(f[0]);}});

    /* Dynamic locations */
    var container=document.getElementById('locationsContainer'),count=0;
    document.getElementById('addLocationBtn').addEventListener('click',function(){
        count++;
        var div=document.createElement('div'); div.className='location-entry';
        div.innerHTML='<div class="location-entry-header"><span class="location-entry-num">Location '+count+'</span>'
            +'<button type="button" class="location-remove-btn" onclick="this.closest(\'.location-entry\').remove()">Remove</button></div>'
            +'<div class="row g-2">'
            +'<div class="col-md-6"><input type="text" class="form-control" name="loc_name[]" placeholder="Store name" required></div>'
            +'<div class="col-md-6"><input type="text" class="form-control" name="loc_note[]" placeholder="Availability"></div>'
            +'<div class="col-12"><input type="text" class="form-control" name="loc_address[]" placeholder="Full address" required></div>'
            +'<div class="col-12"><input type="url" class="form-control" name="loc_map[]" placeholder="Google Maps URL"></div>'
            +'</div>';
        container.appendChild(div);
    });
})();
</script>

<?php
$conn->close();
include '../components/page_close.php';
