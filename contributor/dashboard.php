<?php
/**
 * Contributor Dashboard
 */

define('MULYASUCHI_APP', true);
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Logger.php';
require_once __DIR__ . '/../classes/Validation.php';
require_once __DIR__ . '/../includes/functions.php';

// Require contributor login
Auth::requireRole(ROLE_CONTRIBUTOR, SITE_URL . '/contributor/login.php');

$pageTitle = 'Contributor Dashboard';
$validationObj = new Validation();

// Get user's submission stats
$userId = Auth::getUserId();
$mySubmissions = $validationObj->getContributorHistory($userId, 10);

include __DIR__ . '/../includes/header.php';
?>

<style>
.dashboard-nav {
    background: var(--gradient-success);
    color: white;
    padding: var(--spacing-md);
}

.dashboard-nav .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dashboard-nav h2 {
    margin: 0;
    color: white;
}

.dashboard-nav a {
    color: white;
    margin-left: var(--spacing-md);
}

.dashboard-content {
    padding: var(--spacing-xl);
    max-width: 1280px;
    margin: 0 auto;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-2xl);
}

.stat-card {
    background: white;
    padding: var(--spacing-lg);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    border-top: 4px solid var(--success);
}

.stat-card h3 {
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-bottom: var(--spacing-sm);
}

.stat-card .value {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--success);
}

.action-section {
    background: white;
    padding: var(--spacing-xl);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    margin-bottom: var(--spacing-xl);
}

.action-buttons {
    display: flex;
    gap: var(--spacing-md);
    flex-wrap: wrap;
}

.recent-submissions {
    background: white;
    padding: var(--spacing-xl);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
}

.submission-item {
    padding: var(--spacing-md);
    border-bottom: 1px solid var(--border-color);
}

.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
}

.status-pending { background: #fef3c7; color: #92400e; }
.status-approved { background: #d1fae5; color: #065f46; }
.status-rejected { background: #fee2e2; color: #991b1b; }
</style>

<nav class="dashboard-nav">
    <div class="container">
        <h2>👤 Contributor Dashboard</h2>
        <div>
            <a href="<?php echo SITE_URL; ?>/public/index.php">Public Site</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<main class="dashboard-content">
    <h1>Welcome, <?php echo htmlspecialchars(Auth::getUsername()); ?>!</h1>
    <p style="color: var(--text-secondary); margin-bottom: var(--spacing-xl);">
        Contribute to Nepal's market intelligence platform
    </p>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>TOTAL SUBMISSIONS</h3>
            <div class="value"><?php echo count($mySubmissions); ?></div>
        </div>
        <div class="stat-card">
            <h3>PENDING REVIEW</h3>
            <div class="value">
                <?php 
                echo count(array_filter($mySubmissions, fn($s) => $s['status'] === VALIDATION_PENDING));
                ?>
            </div>
        </div>
        <div class="stat-card">
            <h3>APPROVED</h3>
            <div class="value">
                <?php 
                echo count(array_filter($mySubmissions, fn($s) => $s['status'] === VALIDATION_APPROVED));
                ?>
            </div>
        </div>
    </div>
    
    <div class="action-section">
        <h2>Quick Actions</h2>
        <div class="action-buttons">
            <a href="add_item.php" class="btn-primary">📝 Add New Item</a>
            <a href="update_price.php" class="btn-secondary">💰 Update Price</a>
            <a href="dashboard.php" class="btn-secondary">📊 My Activity</a>
        </div>
    </div>
    
    <div class="recent-submissions">
        <h2>Recent Submissions</h2>
        <?php if (empty($mySubmissions)): ?>
            <p style="color: var(--text-secondary); padding: var(--spacing-lg);">
                No submissions yet. Start contributing!
            </p>
        <?php else: ?>
            <?php foreach ($mySubmissions as $submission): ?>
            <div class="submission-item">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <h4><?php echo htmlspecialchars($submission['item_name'] ?? $submission['existing_item_name'] ?? 'Item'); ?></h4>
                        <p style="font-size: 0.875rem; color: var(--text-secondary);">
                            <?php echo ucfirst($submission['action_type']); ?> | 
                            <?php echo date('M j, Y', strtotime($submission['submitted_at'])); ?>
                        </p>
                    </div>
                    <span class="status-badge status-<?php echo $submission['status']; ?>">
                        <?php echo ucfirst($submission['status']); ?>
                    </span>
                </div>
                <?php if ($submission['status'] === VALIDATION_REJECTED && $submission['rejection_reason']): ?>
                    <p style="color: var(--danger); font-size: 0.875rem; margin-top: var(--spacing-xs);">
                        Reason: <?php echo htmlspecialchars($submission['rejection_reason']); ?>
                    </p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
