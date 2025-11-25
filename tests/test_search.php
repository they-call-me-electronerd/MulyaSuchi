<?php
define('MULYASUCHI_APP', true);
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Item.php';

$itemObj = new Item();

echo "Testing search functionality:\n\n";

// Test 1: No search (should return all items)
$results1 = $itemObj->searchProductsAdvanced([]);
echo "1. No search filter: " . count($results1) . " items\n";

// Test 2: Search for "apple"
$results2 = $itemObj->searchProductsAdvanced(['search' => 'apple']);
echo "2. Search 'apple': " . count($results2) . " items\n";

// Test 3: Search for "Apple"
$results3 = $itemObj->searchProductsAdvanced(['search' => 'Apple']);
echo "3. Search 'Apple': " . count($results3) . " items\n";

// Test 4: Direct SQL query
$db = new Database();
$pdo = $db->getConnection();
$stmt = $pdo->prepare("SELECT * FROM items WHERE status = 'active' AND item_name LIKE ?");
$stmt->execute(['%apple%']);
$results4 = $stmt->fetchAll();
echo "4. Direct SQL 'apple': " . count($results4) . " items\n";

// Test 5: Upper case SQL
$stmt = $pdo->prepare("SELECT * FROM items WHERE status = 'active' AND UPPER(item_name) LIKE UPPER(?)");
$stmt->execute(['%apple%']);
$results5 = $stmt->fetchAll();
echo "5. Direct SQL UPPER 'apple': " . count($results5) . " items\n";

echo "\nFirst result from test 5:\n";
if (count($results5) > 0) {
    print_r($results5[0]);
}
?>
