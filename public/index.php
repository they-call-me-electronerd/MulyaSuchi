<?php
/**
 * Landing Page - Mulyasuchi
 */

// Bootstrap application
define('MULYASUCHI_APP', true);
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Logger.php';
require_once __DIR__ . '/../classes/Item.php';
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'Home';
$additionalCSS = 'public.css';
$additionalJS = 'ticker.js';

// Get categories with item counts
$categoryObj = new Category();
$categories = $categoryObj->getCategoriesWithItemCounts();

// Get recent items
$itemObj = new Item();
$recentItems = $itemObj->getActiveItems(8);

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
?>

<main class="home-page">
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Welcome to <span class="brand-highlight">Mulyasuchi</span></h1>
            <p class="hero-subtitle">मूल्यसूची - Your Trusted Market Intelligence Platform</p>
            <p class="hero-description">Real-time, accurate market prices for items across Nepal. Transparent. Validated. Reliable.</p>
            
            <div class="search-bar-hero">
                <input type="text" id="heroSearch" placeholder="Search for items... तरकारी, फलफूल, इलेक्ट्रोनिक्स..." />
                <button type="button" id="heroSearchBtn">Search</button>
            </div>
        </div>
        
        <div class="hero-graphic">
            <div class="floating-card">
                <span class="icon">📊</span>
                <p>Live Price Updates</p>
            </div>
            <div class="floating-card">
                <span class="icon">✓</span>
                <p>Admin Validated</p>
            </div>
            <div class="floating-card">
                <span class="icon">🇳🇵</span>
                <p>Nepal-Focused</p>
            </div>
        </div>
    </section>
    
    <!-- Live Price Ticker -->
    <section class="price-ticker-section">
        <h3>🔴 Live Price Updates</h3>
        <div class="ticker-wrapper">
            <div class="ticker-content" id="priceTicker">
                <p>Loading price updates...</p>
            </div>
        </div>
    </section>
    
    <!-- Categories Grid -->
    <section class="categories-section">
        <div class="container">
            <h2 class="section-title">Browse by Category</h2>
            <p class="section-subtitle">Explore items organized by categories</p>
            
            <div class="categories-grid">
                <?php foreach ($categories as $category): ?>
                <a href="browse.php?category=<?php echo $category['slug']; ?>" class="category-card">
                    <div class="category-icon">
                        <span class="<?php echo $category['icon_class']; ?>">
                            <?php 
                            // Display emoji based on category
                            $icons = [
                                'vegetables' => '🥦',
                                'fruits' => '🍎',
                                'kitchen-appliances' => '🍳',
                                'study-material' => '📚',
                                'clothing' => '👕',
                                'tools' => '🔧',
                                'electrical-appliances' => '💡',
                                'tech-gadgets' => '📱',
                                'miscellaneous' => '📦'
                            ];
                            echo $icons[$category['slug']] ?? '📦';
                            ?>
                        </span>
                    </div>
                    <h3><?php echo htmlspecialchars($category['category_name']); ?></h3>
                    <p class="category-nepali"><?php echo htmlspecialchars($category['category_name_nepali']); ?></p>
                    <span class="item-count"><?php echo $category['item_count']; ?> items</span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <!-- Featured Items -->
    <?php if (!empty($recentItems)): ?>
    <section class="featured-items-section">
        <div class="container">
            <h2 class="section-title">Recently Updated Prices</h2>
            <p class="section-subtitle">Latest market information</p>
            
            <div class="items-grid">
                <?php foreach ($recentItems as $item): ?>
                <div class="item-card">
                    <div class="item-image">
                        <?php if ($item['image_path']): ?>
                            <img src="<?php echo UPLOAD_URL . $item['image_path']; ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>">
                        <?php else: ?>
                            <div class="no-image">No Image</div>
                        <?php endif; ?>
                    </div>
                    <div class="item-details">
                        <h3><?php echo htmlspecialchars($item['item_name']); ?></h3>
                        <span class="category-tag"><?php echo htmlspecialchars($item['category_name']); ?></span>
                        <p class="item-price"><?php echo formatPrice($item['current_price']); ?> / <?php echo $item['unit']; ?></p>
                        <?php if ($item['market_location']): ?>
                            <p class="item-location">📍 <?php echo htmlspecialchars($item['market_location']); ?></p>
                        <?php endif; ?>
                        <p class="item-updated">Updated <?php echo timeAgo($item['updated_at']); ?></p>
                        <a href="item.php?id=<?php echo $item['item_id']; ?>" class="btn-view">View Details</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="view-all-btn">
                <a href="browse.php" class="btn-primary">View All Items</a>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container">
            <h2>Want to contribute market prices?</h2>
            <p>Join our community of contributors and help build Nepal's most comprehensive market intelligence platform</p>
            <div class="cta-buttons">
                <a href="how-it-works.php" class="btn-secondary">Learn How</a>
                <a href="<?php echo SITE_URL; ?>/contributor/login.php" class="btn-primary">Become a Contributor</a>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
