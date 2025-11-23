<?php
/**
 * Categories Page - Browse All Categories
 * View all product categories with item counts
 */

define('MULYASUCHI_APP', true);
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'Categories - Browse by Category';
$metaDescription = 'Browse all product categories on MulyaSuchi. Find vegetables, fruits, electronics, and more. Track prices across different categories in Nepal.';
$metaKeywords = 'categories, product categories, vegetables, fruits, commodities, nepal market';
$additionalCSS = 'pages/categories.css';

$categoryObj = new Category();
$categories = $categoryObj->getCategoriesWithItemCounts();

include __DIR__ . '/../includes/header_professional.php';
?>

<!-- Hero Section -->
<section class="categories-hero">
    <div class="container">
        <div class="hero-content">
            <h1><i class="bi bi-grid-3x3-gap me-3"></i>Browse Categories</h1>
            <p class="lead">Explore products organized by category. Find exactly what you're looking for.</p>
        </div>
    </div>
</section>

<!-- Categories Grid -->
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2>All Categories</h2>
            <p>Select a category to view products and track prices</p>
        </div>

        <div class="categories-grid">
            <?php if (!empty($categories)): ?>
                <?php 
                $categoryIcons = [
                    'vegetables' => 'bi-basket',
                    'fruits' => 'bi-apple',
                    'kitchen-appliances' => 'bi-house-door',
                    'tech-gadgets' => 'bi-laptop',
                    'clothing' => 'bi-bag',
                    'food-items' => 'bi-egg-fried',
                    'beverages' => 'bi-cup-straw',
                    'dairy' => 'bi-cup',
                    'meat' => 'bi-egg',
                    'grains' => 'bi-flower1'
                ];
                
                foreach ($categories as $category): 
                    $icon = $categoryIcons[$category['slug']] ?? 'bi-box-seam';
                    $itemCount = $category['item_count'] ?? 0;
                ?>
                    <a href="<?php echo SITE_URL; ?>/public/products.php?category=<?php echo htmlspecialchars($category['slug']); ?>" 
                       class="category-card">
                        <div class="category-icon">
                            <i class="bi <?php echo $icon; ?>"></i>
                        </div>
                        <div class="category-info">
                            <h3 class="category-name"><?php echo htmlspecialchars($category['category_name']); ?></h3>
                            <p class="category-count">
                                <i class="bi bi-box me-1"></i>
                                <?php echo $itemCount; ?> <?php echo $itemCount == 1 ? 'item' : 'items'; ?>
                            </p>
                            <?php if (!empty($category['description'])): ?>
                                <p class="category-description">
                                    <?php echo htmlspecialchars(substr($category['description'], 0, 80)); ?>
                                    <?php if (strlen($category['description']) > 80) echo '...'; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="category-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h3>No Categories Found</h3>
                    <p>Categories will appear here once they are created.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Quick Stats -->
        <div class="categories-stats">
            <div class="stat-card">
                <i class="bi bi-grid-3x3-gap"></i>
                <div class="stat-info">
                    <h4><?php echo count($categories); ?></h4>
                    <p>Categories</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="bi bi-box-seam"></i>
                <div class="stat-info">
                    <h4><?php echo array_sum(array_column($categories, 'item_count')); ?></h4>
                    <p>Total Products</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="bi bi-graph-up-arrow"></i>
                <div class="stat-info">
                    <h4>Real-time</h4>
                    <p>Price Updates</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="cta-section">
            <h3>Can't find what you're looking for?</h3>
            <p>Search across all products or browse by specific filters</p>
            <div class="cta-buttons">
                <a href="<?php echo SITE_URL; ?>/public/products.php" class="btn btn-primary">
                    <i class="bi bi-search me-2"></i>Search All Products
                </a>
                <a href="<?php echo SITE_URL; ?>/public/about.php" class="btn btn-secondary">
                    <i class="bi bi-question-circle me-2"></i>Learn More
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer_professional.php'; ?>
