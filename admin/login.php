<?php
/**
 * Admin Login Page
 */

define('MULYASUCHI_APP', true);
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Logger.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect if already logged in
if (Auth::isLoggedIn() && Auth::hasRole(ROLE_ADMIN)) {
    redirect(SITE_URL . '/admin/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $username = sanitizeInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $error = 'Please enter both username and password.';
        } else {
            $auth = new Auth();
            if ($auth->login($username, $password)) {
                // Check if admin role
                if (Auth::hasRole(ROLE_ADMIN)) {
                    redirect(SITE_URL . '/admin/dashboard.php');
                } else {
                    $auth->logout();
                    $error = 'Access denied. Admin login only.';
                }
            } else {
                $error = 'Invalid username or password.';
            }
        }
    }
}

$pageTitle = 'Admin Login';
include __DIR__ . '/../includes/header.php';
?>

<style>
.login-page {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    padding: var(--spacing-xl) var(--spacing-md);
}

.login-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: var(--radius-2xl);
    box-shadow: var(--shadow-2xl);
    max-width: 450px;
    width: 100%;
    padding: var(--spacing-2xl);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.login-header {
    text-align: center;
    margin-bottom: var(--spacing-xl);
}

.login-header h1 {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: var(--spacing-xs);
    font-size: 2rem;
}

.login-header p {
    color: var(--text-secondary);
}

.admin-badge {
    display: inline-block;
    background: var(--gradient-secondary);
    color: white;
    padding: 4px 12px;
    border-radius: var(--radius-md);
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
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

.form-group input {
    width: 100%;
    padding: var(--spacing-sm) var(--spacing-md);
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 1rem;
    transition: all var(--transition-base);
    background: white;
}

.form-group input:focus {
    outline: none;
    border-color: #764ba2;
    box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.1);
}

.error-message {
    background: #fee;
    color: var(--danger);
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-md);
    border-left: 4px solid var(--danger);
}

.btn-login {
    width: 100%;
    padding: var(--spacing-md);
    background: var(--gradient-secondary);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-base);
}

.btn-login:hover {
    box-shadow: var(--shadow-xl);
    transform: translateY(-2px);
}

.login-info {
    background: #f0f9ff;
    border-left: 4px solid #3b82f6;
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--radius-md);
    margin-top: var(--spacing-md);
    font-size: 0.875rem;
}

.login-info strong {
    display: block;
    margin-bottom: 4px;
}

.back-home {
    text-align: center;
    margin-top: var(--spacing-md);
}

.back-home a {
    color: var(--text-secondary);
    font-size: 0.875rem;
}
</style>

<main class="login-page">
    <div class="login-container">
        <div class="login-header">
            <span class="admin-badge">⚡ ADMIN ACCESS</span>
            <h1>Admin Portal</h1>
            <p>Secure login for administrators</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCsrfToken(); ?>">
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus 
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-login">Login to Admin Panel</button>
        </form>
        
        <div class="login-info">
            <strong>Default Credentials (for testing):</strong>
            Username: <code>admin</code><br>
            Password: <code>Admin@123</code><br>
            <small style="color: var(--danger);">⚠️ Change immediately after first login!</small>
        </div>
        
        <div class="back-home">
            <a href="<?php echo SITE_URL; ?>/public/index.php">← Back to Home</a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
