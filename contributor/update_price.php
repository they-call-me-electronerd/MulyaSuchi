<?php
/**
 * Update Item Price - Contributor Panel
 */

define('MULYASUCHI_APP', true);
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Logger.php';
require_once __DIR__ . '/../classes/Item.php';
require_once __DIR__ . '/../classes/Validation.php';
require_once __DIR__ . '/../includes/functions.php';

// Require contributor login
Auth::requireRole(ROLE_CONTRIBUTOR, SITE_URL . '/contributor/login.php');

$pageTitle = 'Update Price';
$error = '';
$success = '';
$selectedItem = null;

// Search functionality
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = sanitizeInput($_GET['search']);
    $itemObj = new Item();
    $searchResults = $itemObj->searchItems($searchTerm, null, 1, 50);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } else {
        $itemId = (int)($_POST['item_id'] ?? 0);
        $newPrice = floatval($_POST['new_price'] ?? 0);
        $marketLocation = sanitizeInput($_POST['market_location'] ?? '');
        $remarks = sanitizeInput($_POST['remarks'] ?? '');
        
        if ($itemId <= 0 || $newPrice <= 0 || empty($marketLocation)) {
            $error = 'Please fill all required fields.';
        } else {
            try {
                $validationObj = new Validation();
                $result = $validationObj->submitPriceUpdate($itemId, $newPrice, $marketLocation, $remarks);
                
                if ($result) {
                    setFlashMessage('Price update submitted! Waiting for admin approval.', 'success');
                    redirect(SITE_URL . '/contributor/dashboard.php');
                } else {
                    $error = 'Failed to submit price update.';
                }
            } catch (Exception $e) {
                error_log("Update price error: " . $e->getMessage());
                $ error = 'An error occurred. Please try again.';
            }
        }
    }
}

// Load item details if ID provided
if (isset($_GET['item_id']) && !empty($_GET['item_id'])) {
    $itemObj = new Item();
    $selectedItem = $itemObj->getItemById((int)$_GET['item_id']);
}

include __DIR__ . '/../includes/header.php';
?>

<style>
.contributor-nav {
    background: var(--gradient-success);
    color: white;
    padding: var(--spacing-md);
}

.contributor-nav .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.contributor-nav a {
    color: white;
    margin-left: var(--spacing-md);
}

.form-container {
    max-width: 800px;
    margin: var(--spacing-2xl) auto;
    padding: 0 var(--spacing-md);
}

.form-card {
    background: white;
    padding: var(--spacing-2xl);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
}

.search-box {
    display: flex;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-xl);
}

.search-box input {
    flex: 1;
    padding: var(--spacing-md);
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 1rem;
}

.search-box button {
    padding: var(--spacing-md) var(--spacing-xl);
    background: var(--gradient-primary);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-weight: 600;
    cursor: pointer;
}

.search-results {
    margin-bottom: var(--spacing-xl);
}

.item-result {
    padding: var(--spacing-md);
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-sm);
    cursor: pointer;
    transition: all var(--transition-base);
}

.item-result:hover {
    border-color: var(--primary-color);
    background: var(--bg-lighter);
}

.selected-item {
    background: var(--bg-lighter);
    padding: var(--spacing-lg);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-xl);
    border-left: 4px solid var(--primary-color);
}

.selected-item h3 {
    margin-bottom: var(--spacing-sm);
}

.current-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin: var(--spacing-sm) 0;
}

.form-group {
    margin-bottom: var(--spacing-md);
}

.form-group label {
    display: block;
    margin-bottom: var(--spacing-xs);
    font-weight: 600;
    color: var(--text-primary);
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: var(--spacing-sm) var(--spacing-md);
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 1rem;
    font-family: inherit;
}

.form-group textarea {
    min-height: 80px;
    resize: vertical;
}

.form-actions {
    display: flex;
    gap: var(--spacing-md);
    margin-top: var(--spacing-xl);
}

.btn-submit {
    flex: 1;
    padding: var(--spacing-md);
    background: var(--gradient-primary);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
}

.btn-cancel {
    padding: var(--spacing-md) var(--spacing-xl);
    background: var(--bg-lighter);
    color: var(--text-primary);
    border: none;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
}
</style>

<nav class="contributor-nav">
    <div class="container">
        <h2>Update Price</h2>
        <div>
            <a href="dashboard.php">← Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<main class="form-container">
    <div class="form-card">
        <h1>Submit Price Update</h1>
        <p style="color: var(--text-secondary); margin-bottom: var(--spacing-xl);">
            Search for an item to update its price. Changes require admin approval.
        </p>
        
        <?php if ($error): ?>
            <div class="flash-message flash-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <!-- Search Box -->
        <form method="GET" action="" class="search-box">
            <input type="text" name="search" placeholder="Search for item name..." 
                   value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" required>
            <button type="submit">Search</button>
        </form>
        
        <!-- Search Results -->
        <?php if (isset($searchResults) && !empty($searchResults['items'])): ?>
            <div class="search-results">
                <h3>Search Results (<?php echo count($searchResults['items']); ?> found)</h3>
                <?php foreach ($searchResults['items'] as $item): ?>
                    <div class="item-result" onclick="selectItem(<?php echo $item['item_id']; ?>)">
                        <strong><?php echo htmlspecialchars($item['item_name']); ?></strong>
                        <?php if (!empty($item['item_name_nepali'])): ?>
                            <span style="color: var(--text-secondary);"> (<?php echo htmlspecialchars($item['item_name_nepali']); ?>)</span>
                        <?php endif; ?>
                        <div style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 4px;">
                            Current: NPR <?php echo number_format($item['current_price'], 2); ?> / <?php echo htmlspecialchars($item['unit']); ?>
                            | <?php echo htmlspecialchars($item['market_location']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif (isset($searchResults)): ?>
            <div class="flash-message flash-error">No items found for "<?php echo htmlspecialchars($_GET['search']); ?>"</div>
        <?php endif; ?>
        
        <!-- Update Form -->
        <?php if ($selectedItem): ?>
            <div class="selected-item">
                <h3><?php echo htmlspecialchars($selectedItem['item_name']); ?></h3>
                <div style="color: var(--text-secondary); margin-bottom: var(--spacing-xs);">
                    Category: <?php echo htmlspecialchars($selectedItem['category_name']); ?>
                </div>
                <div class="current-price">
                    Current Price: NPR <?php echo number_format($selectedItem['current_price'], 2); ?> / <?php echo htmlspecialchars($selectedItem['unit']); ?>
                </div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">
                    Last updated: <?php echo date('M d, Y', strtotime($selectedItem['updated_at'])); ?>
                </div>
            </div>
            
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
                <input type="hidden" name="item_id" value="<?php echo $selectedItem['item_id']; ?>">
                
                <div class="form-group">
                    <label for="new_price">New Price (NPR) <span style="color: var(--danger);">*</span></label>
                    <input type="number" id="new_price" name="new_price" step="0.01" min="0" required
                           placeholder="Enter new price">
                </div>
                
                <div class="form-group">
                    <label for="market_location">Market Location <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="market_location" name="market_location" required
                           placeholder="e.g., Kalimati Vegetable Market"
                           value="<?php echo htmlspecialchars($selectedItem['market_location']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="remarks">Remarks (Optional)</label>
                    <textarea id="remarks" name="remarks" 
                              placeholder="Any additional notes about this price change..."></textarea>
                </div>
                
                <div class="form-actions">
                    <a href="dashboard.php" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit">Submit for Review</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>

<script>
function selectItem(itemId) {
    window.location.href = '?item_id=' + itemId + '<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>';
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
