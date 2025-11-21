<?php
/**
 * Browse Items Page
 */

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

$pageTitle = 'Browse Items';
$additionalCSS = 'public.css';

$itemObj = new Item();
$categoryObj = new Category();

// Get filter parameters
$categorySlug = $_GET['category'] ?? null;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($currentPage - 1) * ITEMS_PER_PAGE;

// Get category details if filtering
$selectedCategory = null;
$categoryId = null;
if ($categorySlug) {
    $selectedCategory = $categoryObj->getCategoryBySlug($categorySlug);
    if ($selectedCategory) {
        $categoryId = $selectedCategory['category_id'];
        $pageTitle = 'Browse ' . $selectedCategory['category_name'];
    }
}

// Get items
$items = $itemObj->getActiveItems(ITEMS_PER_PAGE, $offset, $categoryId);
$totalItems = $itemObj->countItems($categoryId);
$totalPages = ceil($totalItems / ITEMS_PER_PAGE);

// Get all categories for filter
$categories = $categoryObj->getActiveCategories();

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
?>

<main class="browse-page">
    <section class="page-header">
        <div class="container">
            <h1><?php echo $selectedCategory ? htmlspecialchars($selectedCategory['category_name']) : 'All Items'; ?></h1>
            <p class="subtitle">
                <?php 
                if ($selectedCategory) {
                    echo htmlspecialchars($selectedCategory['category_name_nepali']);
                } else {
                    echo 'Browse all available items';
                }
                ?>
            </p>
            <p class="item-count"><?php echo $totalItems; ?> item<?php echo $totalItems != 1 ? 's' : ''; ?> found</p>
        </div>
    </section>
    
    <section class="browse-content">
        <div class="container">
            <div class="browse-layout">
                <!-- Sidebar Filter -->
                <aside class="browse-sidebar">
                    <div class="filter-box">
                        <h3>Categories</h3>
                        <ul class="category-list">
                            <li>
                                <a href="browse.php" class="<?php echo !$categorySlug ? 'active' : ''; ?>">
                                    All Categories
                                    <span class="count"><?php echo $itemObj->countItems(); ?></span>
                                </a>
                            </li>
                            <?php foreach ($categories as $cat): ?>
                            <li>
                                <a href="browse.php?category=<?php echo $cat['slug']; ?>" 
                                   class="<?php echo $categorySlug === $cat['slug'] ? 'active' : ''; ?>">
                                    <?php echo htmlspecialchars($cat['category_name']); ?>
                                    <span class="count"><?php echo $itemObj->countItems($cat['category_id']); ?></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </aside>
                
                <!-- Items Grid -->
                <div class="browse-main">
                    <?php if (empty($items)): ?>
                        <div class="no-items">
                            <h3>No Items Found</h3>
                            <p>There are currently no items in this category.</p>
                            <a href="browse.php" class="btn-primary">View All Items</a>
                        </div>
                    <?php else: ?>
                        <div class="items-grid">
                            <?php foreach ($items as $item): ?>
                            <div class="item-card">
                                <div class="item-image">
                                    <?php if ($item['image_path']): ?>
                                        <img src="<?php echo UPLOAD_URL . $item['image_path']; ?>" 
                                             alt="<?php echo htmlspecialchars($item['item_name']); ?>">
                                    <?php else: ?>
                                        <div class="no-image">No Image</div>
                                    <?php endif; ?>
                                </div>
                                <div class="item-details">
                                    <h3><?php echo htmlspecialchars($item['item_name']); ?></h3>
                                    <span class="category-tag"><?php echo htmlspecialchars($item['category_name']); ?></span>
                                    <p class="item-price"><?php echo formatPrice($item['current_price']); ?> / <?php echo htmlspecialchars($item['unit']); ?></p>
                                    <?php if ($item['market_location']): ?>
                                        <p class="item-location">📍 <?php echo htmlspecialchars($item['market_location']); ?></p>
                                    <?php endif; ?>
                                    <p class="item-updated">Updated <?php echo timeAgo($item['updated_at']); ?></p>
                                    <a href="item.php?id=<?php echo $item['item_id']; ?>" class="btn-view">View Details</a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Pagination -->
                        <?php 
                        $baseUrl = 'browse.php' . ($categorySlug ? '?category=' . $categorySlug . '&' : '?');
                        echo generatePagination($currentPage, $totalPages, rtrim($baseUrl, '?&'));
                        ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
