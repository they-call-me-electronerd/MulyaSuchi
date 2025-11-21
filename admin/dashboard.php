<?php
/**
 * Admin Dashboard
 */

define('MULYASUCHI_APP', true);
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Logger.php';
require_once __DIR__ . '/../classes/Validation.php';
require_once __DIR__ . '/../classes/Item.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../includes/functions.php';

// Require admin login
Auth::requireRole(ROLE_ADMIN, SITE_URL . '/admin/login.php');

$pageTitle = 'Admin Dashboard';

$validationObj = new Validation();
$itemObj = new Item();
$userObj = new User();

$pendingCount = $validationObj->countPendingValidations();
$totalItems = $itemObj->countItems();
$totalUsers = $userObj->countUsers();

include __DIR__ . '/../includes/header.php';
?>

<style>
.admin-nav {
    background: var(--gradient-secondary);
    color: white;
    padding: var(--spacing-md);
}

.admin-nav .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.admin-nav h2 {
    margin: 0;
    color: white;
}

.admin-nav a {
    color: white;
    margin-left: var(--spacing-md);
}

.dashboard-content {
    padding: var(--spacing-xl);
    max-width: 1400px;
    margin: 0 auto;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-2xl);
}

.stat-card {
    background: white;
    padding: var(--spacing-xl);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-primary);
}

.stat-card.warning::before {
    background: var(--gradient-secondary);
}

.stat-card h3 {
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-bottom: var(--spacing-sm);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-card .value {
    font-size: 3rem;
    font-weight: 700;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: var(--spacing-xs);
}

.stat-card.warning .value {
    background: var(--gradient-secondary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.quick-actions {
    background: white;
    padding: var(--spacing-xl);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    margin-bottom: var(--spacing-xl);
}

.action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-md);
    margin-top: var(--spacing-md);
}

.action-card {
    padding: var(--spacing-lg);
    background: var(--bg-lighter);
    border-radius: var(--radius-lg);
    text-align: center;
    transition: all var(--transition-base);
    border: 2px solid transparent;
    text-decoration: none;
    color: var(--text-primary);
}

.action-card:hover {
    border-color: var(--primary-color);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.action-icon {
    font-size: 2.5rem;
    display: block;
    margin-bottom: var(--spacing-sm);
}

.action-card h3 {
    margin: 0 0 var(--spacing-xs) 0;
    font-size: 1rem;
}

.action-card p {
    margin: 0;
    font-size: 0.75rem;
    color: var(--text-secondary);
}
</style>

<nav class="admin-nav">
    <div class="container">
        <h2>⚡ Admin Control Panel</h2>
        <div>
            <a href="<?php echo SITE_URL; ?>/public/index.php">Public Site</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<main class="dashboard-content">
    <h1>Admin Dashboard</h1>
    <p style="color: var(--text-secondary); margin-bottom: var(--spacing-xl);">
        Welcome, <?php echo htmlspecialchars(Auth::getUsername()); ?>! Manage the Mulyasuchi platform.
    </p>
    
    <div class="stats-grid">
        <div class="stat-card warning">
            <h3>Pending Validations</h3>
            <div class="value"><?php echo $pendingCount; ?></div>
            <p style="color: var(--text-muted); font-size: 0.875rem;">Submissions awaiting review</p>
        </div>
        
        <div class="stat-card">
            <h3>Total Items</h3>
            <div class="value"><?php echo $totalItems; ?></div>
            <p style="color: var(--text-muted); font-size: 0.875rem;">Active items in database</p>
        </div>
        
        <div class="stat-card">
            <h3>Total Users</h3>
            <div class="value"><?php echo $totalUsers; ?></div>
            <p style="color: var(--text-muted); font-size: 0.875rem;">Registered contributors</p>
        </div>
        
        <div class="stat-card">
            <h3>System Status</h3>
            <div class="value" style="font-size: 2rem;">✓</div>
            <p style="color: var(--text-muted); font-size: 0.875rem;">All systems operational</p>
        </div>
    </div>
    
    <div class="quick-actions">
        <h2>Quick Actions</h2>
        <div class="action-grid">
            <a href="validation_queue.php" class="action-card">
                <div class="action-icon">✓</div>
                <h3>Validation Queue</h3>
                <p>Review submissions</p>
            </a>
            
            <a href="user_management.php" class="action-card">
                <div class="action-icon">👥</div>
                <h3>Manage Users</h3>
                <p>Create & edit users</p>
            </a>
            
            <a href="<?php echo SITE_URL; ?>/public/browse.php" class="action-card">
                <div class="action-icon">📦</div>
                <h3>View Items</h3>
                <p>All published items</p>
            </a>
            
            <a href="<?php echo SITE_URL; ?>/public/index.php" class="action-card">
                <div class="action-icon">🌐</div>
                <h3>Public Site</h3>
                <p>View live website</p>
            </a>
            
            <a href="dashboard.php" class="action-card">
                <div class="action-icon">📊</div>
                <h3>System Logs</h3>
                <p>Audit trail (coming soon)</p>
            </a>
            
            <a href="dashboard.php" class="action-card">
                <div class="action-icon">⚙️</div>
                <h3>Settings</h3>
                <p>Configuration (coming soon)</p>
            </a>
        </div>
    </div>
    
    <?php if ($pendingCount > 0): ?>
    <div style="background: #fef3c7; padding: var(--spacing-lg); border-radius: var(--radius-lg); border-left: 4px solid #f59e0b;">
        <h3 style="margin: 0 0 var(--spacing-xs) 0; color: #92400e;">⚠️ Action Required</h3>
        <p style="margin: 0; color: #92400e;">
            You have <strong><?php echo $pendingCount; ?></strong> submission<?php echo $pendingCount != 1 ? 's' : ''; ?> pending validation.
            <a href="validation_queue.php" style="color: #92400e; text-decoration: underline; margin-left: var(--spacing-sm);">Review Now →</a>
        </p>
    </div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
