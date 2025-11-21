<?php
/**
 * Contributor Login Page
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
if (Auth::isLoggedIn() && Auth::hasRole(ROLE_CONTRIBUTOR)) {
    redirect(SITE_URL . '/contributor/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                if (Auth::hasRole(ROLE_CONTRIBUTOR)) {
                    redirect(SITE_URL . '/contributor/dashboard.php');
                } else {
                    $auth->logout();
                    $error = 'Access denied. Contributor login only.';
                }
            } else {
                $error = 'Invalid username or password.';
            }
        }
    }
}

$pageTitle = 'Contributor Login';
include __DIR__ . '/../includes/header.php';
?>

<style>
.login-page {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gradient-hero);
    padding: var(--spacing-xl) var(--spacing-md);
}

.login-container {
    background: white;
    border-radius: var(--radius-2xl);
    box-shadow: var(--shadow-2xl);
    max-width: 450px;
    width: 100%;
    padding: var(--spacing-2xl);
}

.login-header {
    text-align: center;
    margin-bottom: var(--spacing-xl);
}

.login-header h1 {
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: var(--spacing-xs);
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

.form-group input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.btn-login {
    width: 100%;
    padding: var(--spacing-md);
    background: var(--gradient-primary);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
}

.btn-login:hover {
    box-shadow: var(--shadow-xl);
    transform: translateY(-2px);
}
</style>

<main class="login-page">
    <div class="login-container">
        <div class="login-header">
            <h1>Contributor Login</h1>
            <p>Sign in to contribute market prices</p>
        </div>
        
        <?php if ($error): ?>
            <div class="flash-message flash-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus 
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-login">Login to Dashboard</button>
        </form>
        
        <div style="text-align: center; margin-top: var(--spacing-md);">
            Don't have an account? 
            <a href="register.php" style="color: var(--primary-color); font-weight: 600;">Register here</a>
        </div>
        
        <div style="text-align: center; margin-top: var(--spacing-md);">
            <a href="<?php echo SITE_URL; ?>/public/index.php" style="color: var(--text-secondary);">← Back to Home</a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
