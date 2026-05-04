<?php

require_once '../config/database.php';
require_login();

$pageTitle       = 'My Books';
$metaDescription = 'Your purchased books on RysuReads.';
$bodyClass       = 'my-books-page';
include '../components/page_open.php';

$userId = (int) (current_user()['id'] ?? 0);

$stmt = $conn->prepare("
    SELECT items.*, categories.name AS category_name, categories.slug AS category_slug,
           purchases.purchased_at
    FROM purchases
    JOIN items ON items.id = purchases.item_id
    JOIN categories ON categories.id = items.category_id
    WHERE purchases.user_id = ?
    ORDER BY purchases.purchased_at DESC
");
$stmt->bind_param('i', $userId);
$stmt->execute();
$books = $stmt->get_result();
$stmt->close();
?>

<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb custom-breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Books</li>
            </ol>
        </nav>
        <div class="page-heading-row">
            <div>
                <span class="eyebrow">Library</span>
                <h1>My Books</h1>
                <p>Books you have purchased on RysuReads.</p>
            </div>
            <a href="/products" class="btn-secondary-action">Browse More</a>
        </div>
    </div>
</section>

<section class="section-block pt-0">
    <div class="container">
        <?php if ($books && $books->num_rows > 0): ?>
            <div class="mybooks-grid">
                <?php while ($book = $books->fetch_assoc()): ?>
                    <article class="mybook-card">
                        <a href="<?php echo e(product_url($book['slug'])); ?>" class="mybook-cover-link">
                            <img src="<?php echo e(image_url($book['image'] ?: 'images/logo.png')); ?>"
                                 alt="<?php echo e($book['name']); ?>"
                                 class="mybook-cover">
                        </a>
                        <div class="mybook-body">
                            <span class="chip"><?php echo e($book['category_name']); ?></span>
                            <h3 class="mybook-title">
                                <a href="<?php echo e(product_url($book['slug'])); ?>"><?php echo e($book['name']); ?></a>
                            </h3>
                            <p class="mybook-desc">
                                <!-- Dual-lang description -->
                                <span class="item-desc-en"><?php echo e(mb_strimwidth($book['description_en'] ?: $book['description'], 0, 100, '...')); ?></span>
                                <span class="item-desc-zh" hidden><?php echo e(mb_strimwidth($book['description'], 0, 100, '...')); ?></span>
                            </p>
                            <div class="mybook-meta">
                                <span class="mybook-price">$<?php echo number_format((float) $book['price'], 2); ?></span>
                                <span class="mybook-date">
                                    Purchased <?php echo date('M j, Y', strtotime($book['purchased_at'])); ?>
                                </span>
                            </div>
                            <div class="mybook-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                Owned
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="mybooks-empty">
                <div class="mybooks-empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                </div>
                <h2>No books yet</h2>
                <p>You haven't purchased any books. Discover your next great read below.</p>
                <a href="/products" class="btn-primary-action">Browse the catalog</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.mybooks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.75rem;
}

.mybook-card {
    background: rgba(252, 248, 242, 0.97);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-soft);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    display: flex;
    flex-direction: column;
}

.mybook-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow);
}

.mybook-cover-link {
    display: block;
    height: 200px;
    overflow: hidden;
}

.mybook-cover {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s ease;
}

.mybook-card:hover .mybook-cover {
    transform: scale(1.04);
}

.mybook-body {
    padding: 1.25rem 1.25rem 1.1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    flex: 1;
}

.mybook-title {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
}

.mybook-title a {
    text-decoration: none;
    color: var(--text);
}

.mybook-title a:hover {
    color: var(--brand-dark);
}

.mybook-desc {
    font-size: 0.84rem;
    color: var(--muted);
    line-height: 1.6;
    margin: 0;
}

.mybook-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    padding-top: 0.65rem;
    border-top: 1px solid var(--border);
}

.mybook-price {
    font-weight: 800;
    font-size: 1rem;
    color: var(--brand-dark);
}

.mybook-date {
    font-size: 0.76rem;
    color: var(--muted);
}

.mybook-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #166534;
    background: rgba(22, 163, 74, 0.10);
    border: 1px solid rgba(22, 163, 74, 0.20);
    padding: 0.25rem 0.65rem;
    border-radius: 999px;
    width: fit-content;
}

.mybooks-empty {
    text-align: center;
    padding: 5rem 1.5rem;
}

.mybooks-empty-icon {
    color: var(--muted);
    margin-bottom: 1.5rem;
    opacity: 0.5;
}

.mybooks-empty h2 {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.mybooks-empty p {
    color: var(--muted);
    margin-bottom: 1.5rem;
}

/* Dark mode */
html[data-theme="dark"] .mybook-card {
    background: rgba(35, 25, 28, 0.98);
    border-color: rgba(255,255,255,0.08);
}

html[data-theme="dark"] .mybook-title a:hover {
    color: var(--accent);
}

html[data-theme="dark"] .mybook-badge {
    background: rgba(22, 163, 74, 0.15);
    color: #86efac;
    border-color: rgba(22, 163, 74, 0.25);
}

html[data-theme="dark"] .mybook-price {
    color: var(--accent);
}

/* Buy button & badge in detail page */
.buy-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.buy-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.55rem 1.1rem;
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 700;
}

.buy-badge-owned {
    background: rgba(22, 163, 74, 0.10);
    color: #166534;
    border: 1px solid rgba(22, 163, 74, 0.22);
}

html[data-theme="dark"] .buy-badge-owned {
    background: rgba(22, 163, 74, 0.15);
    color: #86efac;
    border-color: rgba(22, 163, 74, 0.28);
}
</style>

<script>
(function() {
    function syncDesc() {
        var lang = localStorage.getItem('rysureads-lang') || 'en';
        document.querySelectorAll('.item-desc-en').forEach(function(el) { el.hidden = lang === 'zh'; });
        document.querySelectorAll('.item-desc-zh').forEach(function(el) { el.hidden = lang !== 'zh'; });
    }
    syncDesc();
    document.addEventListener('click', function(e) {
        if (e.target && e.target.hasAttribute('data-lang-toggle')) {
            setTimeout(syncDesc, 50);
        }
    });
})();
</script>

<?php
$conn->close();
include '../components/page_close.php';
