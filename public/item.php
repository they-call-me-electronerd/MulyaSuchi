<?php
/**
 * Item Detail Page
 */

define('MULYASUCHI_APP', true);
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Logger.php';
require_once __DIR__ . '/../classes/Item.php';
require_once __DIR__ . '/../includes/functions.php';

$itemObj = new Item();

// Get item by ID
$itemId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$item = $itemObj->getItemById($itemId);

if (!$item) {
    header('Location: products.php');
    exit;
}

$pageTitle = $item['item_name'];
$metaDescription = "View details and price history for " . htmlspecialchars($item['item_name']) . " in Nepal. Current price: " . formatPrice($item['current_price']);
$additionalCSS = ['pages/item.css'];
$additionalJS = ['https://cdn.jsdelivr.net/npm/chart.js', 'components/chart.js'];

// Get price history
$priceHistory = $itemObj->getPriceHistory($itemId, 30);

// Get tags
$tags = $itemObj->getItemTags($itemId);

include __DIR__ . '/../includes/header_professional.php';
?>

<main class="container my-5">
    <section class="breadcrumb-nav mb-4">
        <a href="index.php">Home</a> / 
        <a href="products.php?category=<?php echo $item['category_slug']; ?>">
            <?php echo htmlspecialchars($item['category_name']); ?>
        </a> / 
        <span><?php echo htmlspecialchars($item['item_name']); ?></span>
    </section>
    
    <section class="item-detail-content">
        <div class="container">
            <div class="item-detail-layout">
                <!-- Item Image -->
                <div class="item-image-section">
                    <?php if ($item['image_path']): ?>
                        <img src="<?php echo UPLOAD_URL . $item['image_path']; ?>" 
                             alt="<?php echo htmlspecialchars($item['item_name']); ?>"
                             class="item-main-image">
                    <?php else: ?>
                        <div class="no-image-large">No Image Available</div>
                    <?php endif; ?>
                </div>
                
                <!-- Item Details -->
                <div class="item-info-section">
                    <h1><?php echo htmlspecialchars($item['item_name']); ?></h1>
                    <span class="category-badge">
                        <?php echo htmlspecialchars($item['category_name']); ?>
                    </span>
                    
                    <div class="price-box">
                        <div class="current-price">
                            <span class="label">Current Price</span>
                            <span class="price"><?php echo formatPrice($item['current_price']); ?></span>
                            <span class="unit">per <?php echo htmlspecialchars($item['unit']); ?></span>
                        </div>
                        
                        <?php if ($item['market_location']): ?>
                            <div class="location-info">
                                <span class="icon">📍</span>
                                <span><?php echo htmlspecialchars($item['market_location']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($item['description']): ?>
                        <div class="description-box">
                            <h3>Description</h3>
                            <p><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($tags)): ?>
                        <div class="tags-box">
                            <h4>Tags</h4>
                            <div class="tags-list">
                                <?php foreach ($tags as $tag): ?>
                                    <span class="tag"><?php echo htmlspecialchars($tag['tag_name']); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="meta-info">
                        <p><strong>Last Updated:</strong> <?php echo date('F j, Y', strtotime($item['updated_at'])); ?></p>
                        <p><strong>Added By:</strong> <?php echo htmlspecialchars($item['created_by_name']); ?></p>
                    </div>
                    
                    <?php if (Auth::isLoggedIn() && Auth::hasRole(ROLE_CONTRIBUTOR)): ?>
                        <div class="action-buttons">
                            <a href="<?php echo SITE_URL; ?>/contributor/submit_price.php?item_id=<?php echo $item['item_id']; ?>" 
                               class="btn-primary">Update Price</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Price History -->
            <?php if (!empty($priceHistory)): ?>
            <div class="price-history-section">
                <h2>Price History</h2>
                <p class="subtitle">Track how the price has changed over time</p>
                
                <div class="price-chart-container">
                    <canvas id="priceChart"></canvas>
                </div>
                
                <div class="price-history-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Old Price</th>
                                <th>New Price</th>
                                <th>Change</th>
                                <th>Updated By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($priceHistory as $history): ?>
                            <tr>
                                <td><?php echo date('M j, Y', strtotime($history['updated_at'])); ?></td>
                                <td><?php echo formatPrice($history['old_price']); ?></td>
                                <td><?php echo formatPrice($history['new_price']); ?></td>
                                <td><?php echo getPriceChangeIndicator($history['price_change_percent']); ?></td>
                                <td><?php echo htmlspecialchars($history['updated_by_name']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<script>
// Price chart data
const priceData = <?php echo json_encode(array_reverse($priceHistory)); ?>;
</script>

<?php include __DIR__ . '/../includes/footer_professional.php'; ?>
