<?php
require_once 'config/database.php';
require_once 'classes/Database.php';

$db = Database::getInstance();
$conn = $db->getConnection();
$stmt = $conn->query('SELECT COUNT(*) as count FROM items');
print_r($stmt->fetch(PDO::FETCH_ASSOC));

$stmt = $conn->query('SELECT c.category_name, COUNT(i.item_id) as item_count FROM categories c LEFT JOIN items i ON c.category_id = i.category_id GROUP BY c.category_id');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
