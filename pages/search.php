<?php

require_once '../config/database.php';

$pageTitle = 'Search';
$metaDescription = 'Search RysuReads titles by name, category, or description.';
$bodyClass = 'search-page';
include '../components/page_open.php';
?>

<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb custom-breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Search</li>
            </ol>
        </nav>
        <div class="page-heading-row">
            <div>
                <h1 data-i18n="search.title">Search titles</h1>
                <p data-i18n="search.copy">Type a title, category, or keyword and the catalog updates as you search.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-block pt-0">
    <div class="container">
        <div class="search-shell">
            <input type="text" id="searchInput" class="form-control form-control-lg" placeholder="Type a product name, category, or keyword" data-i18n-placeholder="search.placeholder" data-ajax-search>
            <div id="searchResults" class="search-results-grid" aria-live="polite"></div>
        </div>
    </div>
</section>

<?php
$conn->close();
include '../components/page_close.php';
