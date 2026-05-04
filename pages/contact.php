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
                <span class="eyebrow" data-i18n="contact.eyebrow">Get in touch</span>
                <h1 data-i18n="contact.title">Contact the reading room</h1>
                <p data-i18n="contact.copy">Use the details below for support, location questions, or account help.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-block pt-0">
    <div class="container">
        <div class="contact-shell contact-page-shell">
            <div>
                <h3 data-i18n="contact.name">Jay</h3>
                <p data-i18n="contact.desc">Online bookstore contact and community links.</p>
            </div>
            <div class="contact-detail-list">
                <a class="contact-link" href="mailto:surya.23007@mhs.unesa.ac.id" data-i18n="contact.email">surya.23007@mhs.unesa.ac.id</a>
                <a class="contact-link" href="tel:+6281263436187" data-i18n="contact.phone">+62 81263436187</a>
                <a class="contact-link" href="https://www.linkedin.com/in/surya-hanjaya/" target="_blank" rel="noopener" data-i18n="contact.linkedin">LinkedIn</a>
                <a class="contact-link" href="https://www.instagram.com/h4njy/" target="_blank" rel="noopener" data-i18n="contact.instagram">Instagram</a>
            </div>
        </div>
    </div>
</section>

<?php
$conn->close();
include '../components/page_close.php';
