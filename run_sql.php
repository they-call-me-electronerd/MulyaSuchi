<?php
require_once 'config/database.php';
require_once 'classes/Database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    $sql = file_get_contents('sql/add_new_categories.sql');
    $conn->exec($sql);
    echo "Successfully added categories.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
