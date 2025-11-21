<?php
/**
 * Navigation Bar
 */
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="main-nav">
    <div class="nav-container">
        <div class="nav-brand">
            <a href="<?php echo SITE_URL; ?>/public/index.php">
                <h1><?php echo SITE_NAME; ?></h1>
                <span class="tagline"><?php echo SITE_TAGLINE; ?></span>
            </a>
        </div>
        
        <button class="nav-toggle" id="navToggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
        
        <ul class="nav-menu" id="navMenu">
            <li><a href="<?php echo SITE_URL; ?>/public/index.php" class="<?php echo $currentPage == 'index.php' ? 'active' : ''; ?>">Home</a></li>
            <li><a href="<?php echo SITE_URL; ?>/public/browse.php" class="<?php echo $currentPage == 'browse.php' ? 'active' : ''; ?>">Browse</a></li>
            <li><a href="<?php echo SITE_URL; ?>/public/search.php" class="<?php echo $currentPage == 'search.php' ? 'active' : ''; ?>">Search</a></li>
            <li><a href="<?php echo SITE_URL; ?>/public/about.php" class="<?php echo $currentPage == 'about.php' ? 'active' : ''; ?>">About</a></li>
            <li><a href="<?php echo SITE_URL; ?>/public/how-it-works.php" class="<?php echo $currentPage == 'how-it-works.php' ? 'active' : ''; ?>">How It Works</a></li>
            
            <?php if (Auth::isLoggedIn()): ?>
                <?php if (Auth::hasRole(ROLE_ADMIN)): ?>
                    <li><a href="<?php echo SITE_URL; ?>/admin/dashboard.php" class="btn-admin">Admin Panel</a></li>
                <?php elseif (Auth::hasRole(ROLE_CONTRIBUTOR)): ?>
                    <li><a href="<?php echo SITE_URL; ?>/contributor/dashboard.php" class="btn-contributor">Dashboard</a></li>
                <?php endif; ?>
                <li><a href="<?php echo SITE_URL; ?>/<?php echo strtolower(Auth::getUserRole()); ?>/logout.php" class="btn-logout">Logout</a></li>
            <?php else: ?>
                <li><a href="<?php echo SITE_URL; ?>/contributor/login.php" class="btn-login">Contributor Login</a></li>
                <li><a href="<?php echo SITE_URL; ?>/admin/login.php" class="btn-login">Admin Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
