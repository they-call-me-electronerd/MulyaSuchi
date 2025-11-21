<?php
/**
 * Admin User Management
 * Create, edit, and manage contributor accounts
 */

define('MULYASUCHI_APP', true);
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Logger.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../includes/functions.php';

// Require admin login
Auth::requireRole(ROLE_ADMIN, SITE_URL . '/admin/login.php');

$pageTitle = 'User Management';
$userObj = new User();

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('Invalid security token', 'error');
    } else {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'create') {
            $username = sanitizeInput($_POST['username'] ?? '');
            $email = sanitizeInput($_POST['email'] ?? '');
            $fullName = sanitizeInput($_POST['full_name'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($username) || empty($email) || empty($fullName) || empty($password)) {
                setFlashMessage('All fields are required', 'error');
            } else {
                $userId = $userObj->createUser($username, $email, $password, $fullName, ROLE_CONTRIBUTOR);
                if ($userId) {
                    setFlashMessage("User '$username' created successfully!", 'success');
                } else {
                    setFlashMessage('Failed to create user', 'error');
                }
            }
        } elseif ($action === 'update_status') {
            $userId = (int)($_POST['user_id'] ?? 0);
            $status = $_POST['status'] ?? '';
            
            if ($userObj->updateUserStatus($userId, $status)) {
                setFlashMessage('User status updated', 'success');
            } else {
                setFlashMessage('Failed to update status', 'error');
            }
        } elseif ($action === 'delete') {
            $userId = (int)($_POST['user_id'] ?? 0);
            if ($userObj->deleteUser($userId)) {
                setFlashMessage('User deleted', 'success');
            } else {
                setFlashMessage('Failed to delete user', 'error');
            }
        }
        redirect(SITE_URL . '/admin/user_management.php');
    }
}

// Get all users
$users = $userObj->getAllUsers();

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

.users-container {
    max-width: 1280px;
    margin: var(--spacing-2xl) auto;
    padding: 0 var(--spacing-md);
}

.create-user-card {
    background: white;
    padding: var(--spacing-xl);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    margin-bottom: var(--spacing-xl);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-md);
}

.form-group {
    margin-bottom: var(--spacing-md);
}

.form-group label {
    display: block;
    margin-bottom: var(--spacing-xs);
    font-weight: 600;
}

.form-group input {
    width: 100%;
    padding: var(--spacing-sm) var(--spacing-md);
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 1rem;
}

.users-table {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.users-table table {
    width: 100%;
    border-collapse: collapse;
}

.users-table th {
    background: var(--bg-lighter);
    padding: var(--spacing-md);
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid var(--border-color);
}

.users-table td {
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

.status-active {
    background: #d1fae5;
    color: #065f46;
}

.status-suspended {
    background: #fee2e2;
    color: #991b1b;
}

.role-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
    background: var(--gradient-primary);
    color: white;
}

.action-dropdown {
    padding: 6px 12px;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
}

.btn-submit {
    padding: var(--spacing-md) var(--spacing-xl);
    background: var(--gradient-primary);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-weight: 600;
    cursor: pointer;
}
</style>

<nav class="admin-nav">
    <div class="container">
        <h2>👥 User Management</h2>
        <div>
            <a href="dashboard.php">← Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<main class="users-container">
    <h1>Manage Users</h1>
    
    <div class="create-user-card">
        <h2>Create New Contributor</h2>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
            <input type="hidden" name="action" value="create">
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Username *</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="full_name" required>
                </div>
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" required>
                </div>
            </div>
            
            <button type="submit" class="btn-submit">Create Contributor</button>
        </form>
    </div>
    
    <div class="users-table">
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <span class="role-badge">
                            <?php echo ucfirst($user['role']); ?>
                        </span>
                    </td>
                    <td>
                        <span class="status-badge status-<?php echo $user['status']; ?>">
                            <?php echo ucfirst($user['status']); ?>
                        </span>
                    </td>
                    <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                    <td>
                        <?php echo $user['last_login'] ? date('M j, Y', strtotime($user['last_login'])) : 'Never'; ?>
                    </td>
                    <td>
                        <?php if ($user['role'] !== ROLE_ADMIN): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
                                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                <input type="hidden" name="action" value="update_status">
                                <select name="status" class="action-dropdown" onchange="this.form.submit()">
                                    <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="suspended" <?php echo $user['status'] === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                                </select>
                            </form>
                        <?php else: ?>
                            <span style="color: var(--text-secondary);">Admin</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
