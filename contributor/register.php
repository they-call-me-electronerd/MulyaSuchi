<?php
/**
 * Contributor Registration Page
 */

define('MULYASUCHI_APP', true);
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect if already logged in
if (Auth::isLoggedIn()) {
    if (Auth::hasRole(ROLE_ADMIN)) {
        redirect(SITE_URL . '/admin/dashboard.php');
    } else {
        redirect(SITE_URL . '/contributor/dashboard.php');
    }
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $fullName = sanitizeInput($_POST['full_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($username) || empty($email) || empty($fullName) || empty($password)) {
        $error = 'All fields are required.';
    } elseif (strlen($password) < PASSWORD_MIN_LENGTH) {
        $error = 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {
        try {
            $userObj = new User();
            
            // Check if username or email already exists
            $db = Database::getInstance();
            $pdo = $db->getConnection();
            
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->fetch()) {
                $error = 'Username or email already exists.';
            } else {
                // Create user
                $userId = $userObj->createUser($username, $email, $password, $fullName, ROLE_CONTRIBUTOR);
                
                if ($userId) {
                    $success = 'Account created successfully! You can now login.';
                    // Auto-login
                    $auth = new Auth();
                    if ($auth->login($username, $password)) {
                        redirect(SITE_URL . '/contributor/dashboard.php');
                    }
                } else {
                    $error = 'Failed to create account. Please try again.';
                }
            }
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $error = 'An error occurred. Please try again later.';
        }
    }
}

$pageTitle = 'Contributor Registration';
include __DIR__ . '/../includes/header.php';
?>

<style>
.register-page {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gradient-success);
    padding: var(--spacing-xl) var(--spacing-md);
}

.register-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: var(--radius-2xl);
    box-shadow: var(--shadow-2xl);
    max-width: 500px;
    width: 100%;
    padding: var(--spacing-2xl);
}

.register-header {
    text-align: center;
    margin-bottom: var(--spacing-xl);
}

.register-header h1 {
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
    color: var(--text-primary);
}

.form-group input {
    width: 100%;
    padding: var(--spacing-sm) var(--spacing-md);
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 1rem;
    transition: all var(--transition-base);
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.error-message {
    background: #fee;
    color: var(--danger);
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-md);
    border-left: 4px solid var(--danger);
}

.success-message {
    background: #d1fae5;
    color: #065f46;
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-md);
    border-left: 4px solid #10b981;
}

.btn-register {
    width: 100%;
    padding: var(--spacing-md);
    background: var(--gradient-primary);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-base);
}

.btn-register:hover {
    box-shadow: var(--shadow-xl);
    transform: translateY(-2px);
}

.login-link {
    text-align: center;
    margin-top: var(--spacing-md);
    color: var(--text-secondary);
}

.password-requirements {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 4px;
}
</style>

<main class="register-page">
    <div class="register-container">
        <div class="register-header">
            <h1>Join Mulyasuchi</h1>
            <p>Become a contributor and help build Nepal's market intelligence</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" required autofocus 
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="full_name">Full Name *</label>
                <input type="text" id="full_name" name="full_name" required
                       value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required>
                <div class="password-requirements">
                    Minimum <?php echo PASSWORD_MIN_LENGTH; ?> characters
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn-register">Create Account</button>
        </form>
        
        <div class="login-link">
            Already have an account? 
            <a href="login.php" style="color: var(--primary-color); font-weight: 600;">Login here</a>
        </div>
        
        <div class="login-link">
            <a href="<?php echo SITE_URL; ?>/public/index.php">← Back to Home</a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
