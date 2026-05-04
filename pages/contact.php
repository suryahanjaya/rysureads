<?php

require_once '../config/database.php';

$pageTitle = 'Contact';
$metaDescription = 'Contact RysuReads for support, location details, and account assistance.';
$bodyClass = 'contact-page';
include '../components/page_open.php';
?>

<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb custom-breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contact</li>
            </ol>
        </nav>
        <div class="page-heading-row">
            <div>
                <span class="eyebrow">Get in touch</span>
                <h1>Contact the reading room</h1>
                <p>Use the details below for support, location questions, or account help.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-block pt-0">
    <div class="container">
        <div class="contact-shell contact-page-shell">
            <div>
                <h3>RysuReads support</h3>
                <p>Questions about the catalog, locations, or account access can be sent to the contact details below.</p>
            </div>
            <div>
                <p class="contact-line">Email: support@rysureads.local</p>
                <p class="contact-line">Phone: +84 900 000 123</p>
                <p class="contact-line">Main office: Ho Chi Minh City</p>
            </div>
        </div>
    </div>
</section>

<?php
$conn->close();
include '../components/page_close.php';
