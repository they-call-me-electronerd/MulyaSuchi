<?php
/**
 * Admin Validation Queue
 * Review and approve/reject contributor submissions
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

// Require admin login
Auth::requireRole(ROLE_ADMIN, SITE_URL . '/admin/login.php');

$pageTitle = 'Validation Queue';
$validationObj = new Validation();

// Handle approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('Invalid security token', 'error');
    } else {
        $queueId = (int)($_POST['queue_id'] ?? 0);
        $action = $_POST['action'] ?? '';
        
        if ($action === 'approve') {
            $result = $validationObj->approveSubmission($queueId);
            if ($result) {
                setFlashMessage('Submission approved successfully!', 'success');
            } else {
                setFlashMessage('Failed to approve submission', 'error');
            }
        } elseif ($action === 'reject') {
            $reason = sanitizeInput($_POST['rejection_reason'] ?? '');
            if (empty($reason)) {
                setFlashMessage('Please provide a rejection reason', 'error');
            } else {
                $result = $validationObj->rejectSubmission($queueId, $reason);
                if ($result) {
                    setFlashMessage('Submission rejected', 'success');
                } else {
                    setFlashMessage('Failed to reject submission', 'error');
                }
            }
        }
        redirect(SITE_URL . '/admin/validation_queue.php');
    }
}

// Get pending submissions
$pendingSubmissions = $validationObj->getPendingValidations();

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

.admin-nav a {
    color: white;
    margin-left: var(--spacing-md);
}

.queue-container {
    max-width: 1280px;
    margin: var(--spacing-2xl) auto;
    padding: 0 var(--spacing-md);
}

.queue-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-xl);
}

.stat-card {
    background: white;
    padding: var(--spacing-lg);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
}

.stat-card h3 {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin-bottom: var(--spacing-sm);
}

.stat-card .value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
}

.submission-card {
    background: white;
    padding: var(--spacing-xl);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    margin-bottom: var(--spacing-lg);
}

.submission-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: var(--spacing-md);
    padding-bottom: var(--spacing-md);
    border-bottom: 2px solid var(--border-color);
}

.submission-type {
    display: inline-block;
    padding: 4px 12px;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 600;
    background: var(--gradient-primary);
    color: white;
}

.submission-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
}

.detail-item {
    padding: var(--spacing-sm);
    background: var(--bg-lighter);
    border-radius: var(--radius-md);
}

.detail-item strong {
    display: block;
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin-bottom: 4px;
}

.action-buttons {
    display: flex;
    gap: var(--spacing-md);
    margin-top: var(--spacing-md);
}

.btn-approve {
    flex: 1;
    padding: var(--spacing-md);
    background: var(--success);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-weight: 600;
    cursor: pointer;
}

.btn-reject {
    flex: 1;
    padding: var(--spacing-md);
    background: var(--danger);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-weight: 600;
    cursor: pointer;
}

.reject-form {
    display: none;
    margin-top: var(--spacing-md);
    padding: var(--spacing-md);
    background: #fee;
    border-radius: var(--radius-md);
}

.reject-form textarea {
    width: 100%;
    padding: var(--spacing-sm);
    border: 2px solid var(--danger);
    border-radius: var(--radius-md);
    min-height: 80px;
    margin-bottom: var(--spacing-sm);
    font-family: inherit;
}

.no-submissions {
    text-align: center;
    padding: var(--spacing-2xl);
    color: var(--text-secondary);
}
</style>

<nav class="admin-nav">
    <div class="container">
        <h2>⚡ Validation Queue</h2>
        <div>
            <a href="dashboard.php">← Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<main class="queue-container">
    <h1>Review Submissions</h1>
    <p style="color: var(--text-secondary); margin-bottom: var(--spacing-xl);">
        Approve or reject contributor submissions to maintain data quality
    </p>
    
    <div class="queue-stats">
        <div class="stat-card">
            <h3>PENDING REVIEW</h3>
            <div class="value"><?php echo count($pendingSubmissions); ?></div>
        </div>
    </div>
    
    <?php if (empty($pendingSubmissions)): ?>
        <div class="no-submissions">
            <h2>🎉 All caught up!</h2>
            <p>No pending submissions at the moment.</p>
        </div>
    <?php else: ?>
        <?php foreach ($pendingSubmissions as $submission): ?>
            <div class="submission-card">
                <div class="submission-header">
                    <div>
                        <span class="submission-type">
                            <?php echo $submission['action_type'] === ACTION_NEW_ITEM ? '📝 New Item' : '💰 Price Update'; ?>
                        </span>
                        <h3 style="margin-top: var(--spacing-sm);">
                            <?php echo htmlspecialchars($submission['item_name'] ?? $submission['existing_item_name'] ?? 'Item'); ?>
                        </h3>
                        <p style="font-size: 0.875rem; color: var(--text-secondary);">
                            Submitted by: <strong><?php echo htmlspecialchars($submission['contributor_name']); ?></strong> | 
                            <?php echo date('M j, Y g:i A', strtotime($submission['submitted_at'])); ?>
                        </p>
                    </div>
                </div>
                
                <div class="submission-details">
                    <?php if ($submission['action_type'] === ACTION_NEW_ITEM): ?>
                        <div class="detail-item">
                            <strong>CATEGORY</strong>
                            <?php echo htmlspecialchars($submission['category_name'] ?? 'N/A'); ?>
                        </div>
                        <div class="detail-item">
                            <strong>INITIAL PRICE</strong>
                            NPR <?php echo number_format($submission['base_price'], 2); ?> / <?php echo htmlspecialchars($submission['unit']); ?>
                        </div>
                        <div class="detail-item">
                            <strong>MARKET LOCATION</strong>
                            <?php echo htmlspecialchars($submission['market_location']); ?>
                        </div>
                        <?php if (!empty($submission['description'])): ?>
                            <div class="detail-item" style="grid-column: 1 / -1;">
                                <strong>DESCRIPTION</strong>
                                <?php echo htmlspecialchars($submission['description']); ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="detail-item">
                            <strong>CURRENT PRICE</strong>
                            NPR <?php echo number_format($submission['current_price'], 2); ?>
                        </div>
                        <div class="detail-item">
                            <strong>NEW PRICE</strong>
                            NPR <?php echo number_format($submission['new_price'], 2); ?>
                        </div>
                        <div class="detail-item">
                            <strong>CHANGE</strong>
                            <?php
                            $change = (($submission['new_price'] - $submission['current_price']) / $submission['current_price']) * 100;
                            $color = $change > 0 ? 'var(--danger)' : 'var(--success)';
                            echo "<span style='color: $color; font-weight: 700;'>";
                            echo ($change > 0 ? '+' : '') . number_format($change, 1) . '%';
                            echo "</span>";
                            ?>
                        </div>
                        <div class="detail-item">
                            <strong>MARKET LOCATION</strong>
                            <?php echo htmlspecialchars($submission['market_location']); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="action-buttons">
                    <form method="POST" style="flex: 1;">
                        <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
                        <input type="hidden" name="queue_id" value="<?php echo $submission['queue_id']; ?>">
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" class="btn-approve" onclick="return confirm('Approve this submission?')">
                            ✓ Approve
                        </button>
                    </form>
                    
                    <button type="button" class="btn-reject" onclick="toggleRejectForm(<?php echo $submission['queue_id']; ?>)">
                        ✗ Reject
                    </button>
                </div>
                
                <div id="reject-form-<?php echo $submission['queue_id']; ?>" class="reject-form">
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
                        <input type="hidden" name="queue_id" value="<?php echo $submission['queue_id']; ?>">
                        <input type="hidden" name="action" value="reject">
                        <label><strong>Rejection Reason:</strong></label>
                        <textarea name="rejection_reason" required placeholder="Explain why this submission is being rejected..."></textarea>
                        <div style="display: flex; gap: var(--spacing-sm);">
                            <button type="submit" class="btn-reject">Confirm Rejection</button>
                            <button type="button" onclick="toggleRejectForm(<?php echo $submission['queue_id']; ?>)" 
                                    style="background: var(--bg-lighter); color: var(--text-primary);">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<script>
function toggleRejectForm(queueId) {
    const form = document.getElementById('reject-form-' + queueId);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
