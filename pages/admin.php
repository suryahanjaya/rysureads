<?php

require_once '../config/database.php';
require_admin();

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['_action'] ?? '') === 'delete') {
    $delId = (int) ($_POST['item_id'] ?? 0);
    if ($delId > 0) {
        $conn->query("DELETE FROM items WHERE id = $delId");
    }
    header('Location: /admin');
    exit;
}

$pageTitle = 'Admin — Manage Items';
$metaDescription = 'Admin panel for managing RysuReads catalog items.';
$bodyClass = 'admin-page';
include '../components/page_open.php';

$items = $conn->query("
    SELECT items.*, categories.name AS category_name
    FROM items
    JOIN categories ON categories.id = items.category_id
    ORDER BY items.created_at DESC
");

$success = app_flash('success');
$error   = app_flash('error');
?>

<section class="page-hero">
    <div class="container">
        <div class="page-heading-row">
            <div>
                <span class="eyebrow">Admin</span>
                <h1>Manage Catalog</h1>
                <p>Add, view, or remove books from the catalog.</p>
            </div>
            <a href="/create-item" class="btn-primary-action">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add New Book
            </a>
        </div>
    </div>
</section>

<section class="section-block pt-0">
    <div class="container">
        <?php if ($success): ?>
            <div class="admin-alert admin-alert-success"><?php echo e($success); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="admin-alert admin-alert-error"><?php echo e($error); ?></div>
        <?php endif; ?>

        <div class="admin-table-shell">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="col-img">Cover</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th class="col-price">Price</th>
                        <th class="col-rating">Rating</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($items && $items->num_rows > 0): ?>
                        <?php while ($item = $items->fetch_assoc()): ?>
                            <tr>
                                <td class="col-img">
                                    <img src="<?php echo e(image_url($item['image'] ?: 'images/logo.png')); ?>"
                                         alt="<?php echo e($item['name']); ?>"
                                         class="admin-thumb">
                                </td>
                                <td>
                                    <strong class="admin-item-name"><?php echo e($item['name']); ?></strong>
                                    <p class="admin-item-desc"><?php echo e(mb_strimwidth($item['description'], 0, 60, '...')); ?></p>
                                </td>
                                <td><span class="chip"><?php echo e($item['category_name']); ?></span></td>
                                <td class="col-price"><strong>$<?php echo number_format((float) $item['price'], 2); ?></strong></td>
                                <td class="col-rating">&#9733; <?php echo number_format((float) $item['rating'], 1); ?></td>
                                <td class="col-actions">
                                    <a href="<?php echo e(product_url($item['slug'])); ?>" class="admin-action-btn admin-btn-view" target="_blank" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <form method="POST" action="/admin" class="admin-delete-form"
                                          onsubmit="return confirm('Delete \'<?php echo e(addslashes($item['name'])); ?>\'? This cannot be undone.')">
                                        <input type="hidden" name="_action" value="delete">
                                        <input type="hidden" name="item_id" value="<?php echo (int) $item['id']; ?>">
                                        <button type="submit" class="admin-action-btn admin-btn-delete" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="admin-empty">No items yet. <a href="/create-item">Add the first book →</a></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<style>
.admin-table-shell {
    background: rgba(252, 248, 242, 0.97);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow);
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table thead {
    background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
    color: #fff;
}

.admin-table thead th {
    padding: 1rem 1.1rem;
    text-align: left;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    white-space: nowrap;
}

.admin-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background 0.15s;
}

.admin-table tbody tr:last-child {
    border-bottom: none;
}

.admin-table tbody tr:hover {
    background: rgba(122, 31, 43, 0.03);
}

.admin-table td {
    padding: 0.85rem 1.1rem;
    vertical-align: middle;
    font-size: 0.9rem;
}

.col-img { width: 64px; }
.col-price { width: 90px; text-align: right; }
.col-rating { width: 80px; text-align: center; color: var(--muted); }
.col-actions { width: 100px; text-align: center; }

.admin-thumb {
    width: 52px;
    height: 68px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(64,19,26,0.12);
}

.admin-item-name {
    display: block;
    font-size: 0.93rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 0.2rem;
}

.admin-item-desc {
    font-size: 0.78rem;
    color: var(--muted);
    margin: 0;
    line-height: 1.4;
}

.admin-delete-form {
    display: inline;
}

.admin-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: background 0.15s, transform 0.15s;
    text-decoration: none;
    margin: 0 0.15rem;
}

.admin-btn-view {
    background: rgba(122, 31, 43, 0.08);
    color: var(--brand-dark);
}

.admin-btn-view:hover {
    background: rgba(122, 31, 43, 0.16);
    transform: scale(1.08);
}

.admin-btn-delete {
    background: rgba(220, 38, 38, 0.08);
    color: #dc2626;
}

.admin-btn-delete:hover {
    background: rgba(220, 38, 38, 0.18);
    transform: scale(1.08);
}

.admin-alert {
    padding: 0.85rem 1.1rem;
    border-radius: 12px;
    margin-bottom: 1.25rem;
    font-weight: 600;
    font-size: 0.9rem;
}

.admin-alert-success {
    background: rgba(22, 163, 74, 0.1);
    color: #166534;
    border: 1px solid rgba(22, 163, 74, 0.2);
}

.admin-alert-error {
    background: rgba(220, 38, 38, 0.08);
    color: #991b1b;
    border: 1px solid rgba(220, 38, 38, 0.18);
}

.admin-empty {
    text-align: center;
    padding: 2.5rem;
    color: var(--muted);
}

.admin-empty a {
    color: var(--brand-dark);
    font-weight: 700;
}

html[data-theme="dark"] .admin-table-shell {
    background: rgba(35, 25, 28, 0.98);
}

html[data-theme="dark"] .admin-table tbody tr:hover {
    background: rgba(255,255,255,0.03);
}

html[data-theme="dark"] .admin-item-name {
    color: var(--text);
}

html[data-theme="dark"] .admin-btn-view {
    background: rgba(255,255,255,0.07);
    color: var(--text);
}

html[data-theme="dark"] .admin-alert-success {
    background: rgba(22,163,74,0.12);
    color: #86efac;
    border-color: rgba(22,163,74,0.25);
}

html[data-theme="dark"] .admin-alert-error {
    background: rgba(220,38,38,0.12);
    color: #fca5a5;
    border-color: rgba(220,38,38,0.25);
}

@media (max-width: 767.98px) {
    .admin-table thead .col-rating,
    .admin-table td.col-rating { display: none; }
    .admin-table thead .col-price,
    .admin-table td.col-price { display: none; }
    .admin-item-desc { display: none; }
}
</style>

<?php
$conn->close();
include '../components/page_close.php';
