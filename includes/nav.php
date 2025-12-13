<?php
/**
 * Enhanced Modern Liquid Glass Navigation Bar
 * With Glassmorphism, Smooth Animations, and Premium Design
 */
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="modern-nav">
    <!-- Animated background elements -->
    <div class="nav-backdrop"></div>
    <div class="nav-blur-container">
        <div class="nav-glass-effect"></div>
    </div>
    
    <div class="nav-container">
        <!-- Brand Section with Logo Animation -->
        <div class="nav-brand-wrapper">
            <a href="<?php echo SITE_URL; ?>/public/index.php" class="nav-brand">
                <div class="brand-icon-wrapper">
                    <span class="brand-icon">💎</span>
                    <div class="brand-glow"></div>
                </div>
                <div class="brand-text-wrapper">
                    <h1 class="brand-name"><?php echo SITE_NAME; ?></h1>
                    <span class="brand-tagline"><?php echo SITE_TAGLINE; ?></span>
                </div>
            </a>
        </div>
        
        <!-- Main Navigation Menu with Smooth Transitions -->
        <div class="nav-menu-wrapper">
            <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation menu">
                <span class="hamburger-line line-1"></span>
                <span class="hamburger-line line-2"></span>
                <span class="hamburger-line line-3"></span>
                <span class="hamburger-bg"></span>
            </button>
            
            <ul class="nav-menu" id="navMenu">
                <li class="nav-item">
                    <a href="<?php echo SITE_URL; ?>/public/index.php" class="nav-link <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">🏠</span>
                        <span class="nav-text">Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_URL; ?>/public/products.php" class="nav-link <?php echo $currentPage == 'products.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">🛍️</span>
                        <span class="nav-text">Products</span>
                    </a>
                </li>
                
                <!-- Search Box -->
                <li class="nav-item nav-search-item">
                    <div class="nav-search-box">
                        <input type="text" 
                               class="nav-search-input" 
                               placeholder="Search by Inspiration" 
                               id="navSearchInput">
                        <button class="nav-search-btn" aria-label="Search">
                            <span class="search-icon">🔍</span>
                        </button>
                    </div>
                </li>
                
                <li class="nav-item">
                    <a href="<?php echo SITE_URL; ?>/public/categories.php" class="nav-link <?php echo $currentPage == 'categories.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">📂</span>
                        <span class="nav-text">Categories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_URL; ?>/public/about.php" class="nav-link <?php echo $currentPage == 'about.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">ℹ️</span>
                        <span class="nav-text">About</span>
                    </a>
                </li>
                
                <!-- Authentication Links with Premium Styling -->
                <li class="nav-divider"></li>
                
                <?php if (Auth::isLoggedIn()): ?>
                    <?php if (Auth::hasRole(ROLE_ADMIN)): ?>
                        <li class="nav-item">
                            <a href="<?php echo SITE_URL; ?>/admin/dashboard.php" class="nav-link nav-link-admin">
                                <span class="nav-icon">👑</span>
                                <span class="nav-text">Admin Panel</span>
                                <span class="nav-badge">Admin</span>
                            </a>
                        </li>
                    <?php elseif (Auth::hasRole(ROLE_CONTRIBUTOR)): ?>
                        <li class="nav-item">
                            <a href="<?php echo SITE_URL; ?>/contributor/dashboard.php" class="nav-link nav-link-contributor">
                                <span class="nav-icon">📊</span>
                                <span class="nav-text">Dashboard</span>
                                <span class="nav-badge">Contributor</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a href="<?php echo SITE_URL; ?>/<?php echo strtolower(Auth::getUserRole()); ?>/logout.php" class="nav-link nav-link-logout">
                            <span class="nav-icon">🚪</span>
                            <span class="nav-text">Logout</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="<?php echo SITE_URL; ?>/contributor/login.php" class="nav-link nav-link-auth nav-link-contributor">
                            <span class="nav-icon">🔐</span>
                            <span class="nav-text">Contributor</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo SITE_URL; ?>/admin/login.php" class="nav-link nav-link-auth nav-link-admin">
                            <span class="nav-icon">👑</span>
                            <span class="nav-text">Admin</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        
        <!-- Additional Actions (Search, Notifications, etc.) -->
        <div class="nav-actions">
            <button class="nav-action-btn" aria-label="Search" title="Search">
                <span class="action-icon">🔍</span>
            </button>
            <button class="nav-action-btn theme-toggle" aria-label="Toggle theme" title="Toggle dark/light mode">
                <span class="action-icon">🌙</span>
            </button>
        </div>
    </div>
</nav>
