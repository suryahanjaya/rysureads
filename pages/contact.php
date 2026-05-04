<?php

require_once '../config/database.php';

$pageTitle = 'Contact';
$metaDescription = 'Contact Surya Hanjaya, creator of RysuReads — the refined reading catalog.';
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
                <span class="eyebrow" data-i18n="contact.eyebrow">Creator</span>
                <h1 data-i18n="contact.title">Meet the Creator</h1>
                <p data-i18n="contact.copy">RysuReads was designed and built by Surya Hanjaya. Reach out for collaboration, feedback, or just to say hi.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-block pt-0">
    <div class="container">
        <div class="creator-shell">
            <div class="creator-avatar-col">
                <div class="creator-avatar">
                    <img src="<?php echo e(image_url('images/00.png')); ?>" alt="Surya Hanjaya" class="creator-avatar-img">
                </div>
            </div>
            <div class="creator-info-col">
                <span class="creator-badge" data-i18n="contact.badge">Developer &amp; Designer</span>
                <h2 class="creator-name" data-i18n="contact.name">Surya Hanjaya</h2>
                <p class="creator-bio" data-i18n="contact.bio">A passionate web developer and book lover from Indonesia. RysuReads is a personal project built to make discovering books a refined, enjoyable experience.</p>

                <div class="creator-links">
                    <a class="creator-link creator-link-email" href="mailto:surya.23007@mhs.unesa.ac.id">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
                        surya.23007@mhs.unesa.ac.id
                    </a>
                    <a class="creator-link creator-link-phone" href="tel:+6281263436187">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.6 3.18 2 2 0 0 1 3.56 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.54a16 16 0 0 0 5.55 5.55l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16.92z"/></svg>
                        +62 81263436187
                    </a>
                    <a class="creator-link creator-link-linkedin" href="https://www.linkedin.com/in/surya-hanjaya/" target="_blank" rel="noopener">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                        LinkedIn — surya-hanjaya
                    </a>
                    <a class="creator-link creator-link-instagram" href="https://www.instagram.com/h4njy/" target="_blank" rel="noopener">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        Instagram — @h4njy
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$conn->close();
include '../components/page_close.php';
