<?php
/**
 * Authentication Class
 * 
 * Handles user authentication, session management, and authorization
 */

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Logger.php';

class Auth {
    private $pdo;
    
    public function __construct() {
        $db = Database::getInstance();
        $this->pdo = $db->getConnection();
    }
    
    /**
     * Authenticate user with username and password
     */
    public function login($username, $password) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT user_id, username, email, password_hash, full_name, role, status
                FROM users
                WHERE username = :username AND status = :status
            ");
            
            $stmt->execute([
                'username' => $username,
                'status' => STATUS_ACTIVE
            ]);
            
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['logged_in'] = true;
                
                // Update last login
                $this->updateLastLogin($user['user_id']);
                
                // Log the login
                Logger::log(LOG_LOGIN, 'user', $user['user_id'], "User {$username} logged in");
                
                return true;
            }
            
            return false;
            
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update last login timestamp
     */
    private function updateLastLogin($userId) {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $userId]);
        } catch (PDOException $e) {
            error_log("Update last login error: " . $e->getMessage());
        }
    }
    
    /**
     * Logout user
     */
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            Logger::log(LOG_LOGOUT, 'user', $_SESSION['user_id'], "User logged out");
        }
        
        session_unset();
        session_destroy();
        
        // Start a new session for flash messages
        session_start();
    }
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    /**
     * Check if user has specific role
     */
    public static function hasRole($role) {
        return self::isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === $role;
    }
    
    /**
     * Require login (redirect if not logged in)
     */
    public static function requireLogin($redirectTo = '/public/index.php') {
        if (!self::isLoggedIn()) {
            $_SESSION['error'] = "Please login to continue";
            header("Location: " . SITE_URL . $redirectTo);
            exit;
        }
    }
    
    /**
     * Require specific role
     */
    public static function requireRole($role, $redirectTo = '/public/index.php') {
        self::requireLogin();
        
        if (!self::hasRole($role)) {
            $_SESSION['error'] = "Access denied";
            header("Location: " . SITE_URL . $redirectTo);
            exit;
        }
    }
    
    /**
     * Generate CSRF token
     */
    public static function generateCSRFToken() {
        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }
    
    /**
     * Verify CSRF token
     */
    public static function verifyCSRFToken($token) {
        return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }
    
    /**
     * Get current user ID
     */
    public static function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current user role
     */
    public static function getUserRole() {
        return $_SESSION['role'] ?? null;
    }
    
    /**
     * Get current username
     */
    public static function getUsername() {
        return $_SESSION['username'] ?? null;
    }
}

?>
