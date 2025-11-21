<?php
/**
 * Site Configuration
 * 
 * Global configuration settings for the Mulyasuchi platform
 */

// Prevent direct access
if (!defined('MULYASUCHI_APP')) {
    die('Direct access not permitted');
}

// Site Information
define('SITE_NAME', 'Mulyasuchi');
define('SITE_TAGLINE', 'Your Trusted Market Intelligence Platform');
define('SITE_URL', 'http://localhost/MulyaSuchi');
define('SITE_EMAIL', 'contact@mulyasuchi.com');

// File Upload Configuration
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/items/');
define('UPLOAD_URL', SITE_URL . '/assets/uploads/items/');
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_IMAGE_TYPES', serialize(['image/jpeg', 'image/png', 'image/jpg', 'image/webp']));

// Pagination
define('ITEMS_PER_PAGE', 20);

// Session Configuration
define('SESSION_LIFETIME', 3600); // 1 hour in seconds
define('SESSION_NAME', 'MULYASUCHI_SESSION');

// Security
define('CSRF_TOKEN_NAME', 'csrf_token');
define('PASSWORD_MIN_LENGTH', 8);

// Timezone
date_default_timezone_set('Asia/Kathmandu');

// Error Reporting (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start([
        'cookie_lifetime' => SESSION_LIFETIME,
        'cookie_httponly' => true,
        'cookie_secure' => false, // Set to true in production with HTTPS
        'use_strict_mode' => true
    ]);
}

?>
