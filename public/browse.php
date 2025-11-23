<?php
/**
 * Browse Items Page - Redirect to Products
 */

require_once __DIR__ . '/../config/config.php';

// Preserve query parameters
$queryString = $_SERVER['QUERY_STRING'];
$redirectUrl = 'products.php';

if (!empty($queryString)) {
    $redirectUrl .= '?' . $queryString;
}

header("Location: " . $redirectUrl);
exit;
?>
