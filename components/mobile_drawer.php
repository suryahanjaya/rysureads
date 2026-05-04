<?php
// Variables are inherited from the page scope (set in header.php)
$_drawerUser      = isset($user) ? $user : null;
$_drawerIsAdmin   = isset($isAdmin) ? $isAdmin : false;
$_drawerActivePath = isset($activePath) ? $activePath : '/';
$_drawerLogoPath  = isset($logoPath) ? $logoPath : image_url('images/logo.png');
?>
<!-- Mobile drawer: must live outside <header> so position:fixed is relative to viewport,
     not to the header's backdrop-filter stacking context. -->
<div class="nav-backdrop" data-nav-backdrop hidden></div>
<div class="mobile-drawer" data-mobile-drawer>

    <!-- Drawer top bar -->
    <div class="mobile-drawer-header">
        <a class="brand-lockup" href="/" style="gap:0.6rem">
            <img src="<?php echo e($_drawerLogoPath); ?>" alt="RysuReads" class="brand-mark" style="width:34px;height:34px">
            <span class="brand-tagline-only" style="font-size:0.7rem">Read More. Grow More.</span>
        </a>
        <button class="mobile-drawer-close" type="button" data-mobile-close aria-label="Close">&times;</button>
    </div>

    <!-- User info strip -->
    <?php if ($_drawerUser): ?>
    <div class="mobile-drawer-user">
        <div>
            <p class="mobile-drawer-greeting">Hi, <?php echo e($_drawerUser['name']); ?></p>
            <p class="mobile-drawer-role"><?php echo $_drawerIsAdmin ? 'Administrator' : 'Member'; ?></p>
        </div>
        <?php if ($_drawerIsAdmin): ?>
            <a href="/admin" class="mobile-drawer-admin-chip">Admin Panel</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Nav links -->
    <nav class="mobile-drawer-nav">
        <a href="/" class="mobile-drawer-link<?php echo $_drawerActivePath === '/' ? ' active' : ''; ?>">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span data-i18n="nav.home">Home</span>
        </a>
        <a href="/products" class="mobile-drawer-link<?php echo str_starts_with($_drawerActivePath, '/products') ? ' active' : ''; ?>">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            <span data-i18n="nav.products">Products</span>
        </a>
        <a href="/search" class="mobile-drawer-link<?php echo $_drawerActivePath === '/search' ? ' active' : ''; ?>">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <span data-i18n="nav.search">Search</span>
        </a>
        <a href="/contact" class="mobile-drawer-link<?php echo $_drawerActivePath === '/contact' ? ' active' : ''; ?>">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span data-i18n="nav.contact">Contact</span>
        </a>
        <?php if ($_drawerUser && !$_drawerIsAdmin): ?>
        <a href="/my-books" class="mobile-drawer-link<?php echo $_drawerActivePath === '/my-books' ? ' active' : ''; ?>">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            <span>My Books</span>
        </a>
        <?php endif; ?>
    </nav>

    <!-- Auth footer -->
    <div class="mobile-drawer-footer">
        <?php if ($_drawerUser): ?>
            <a href="/logout" class="mobile-drawer-logout-btn" data-i18n="header.logout">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Logout
            </a>
        <?php else: ?>
            <a href="/login" class="mobile-drawer-auth-solid" data-i18n="header.login">Login</a>
            <a href="/register" class="mobile-drawer-auth-outline" data-i18n="header.register">Register</a>
        <?php endif; ?>
    </div>
</div>
