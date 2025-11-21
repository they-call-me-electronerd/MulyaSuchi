<?php
/**
 * Item Class
 * 
 * Handles all item-related operations
 */

class Item {
    private $pdo;
    
    public function __construct() {
        $db = Database::getInstance();
        $this->pdo = $db->getConnection();
    }
    
    /**
     * Get all active items
     */
    public function getActiveItems($limit = null, $offset = 0, $categoryId = null) {
        try {
            $sql = "
                SELECT i.*, c.category_name, c.slug as category_slug
                FROM items i
                JOIN categories c ON i.category_id = c.category_id
                WHERE i.status = :status
            ";
            
            if ($categoryId) {
                $sql .= " AND i.category_id = :category_id";
            }
            
            $sql .= " ORDER BY i.updated_at DESC";
            
            if ($limit) {
                $sql .= " LIMIT :limit OFFSET :offset";
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':status', ITEM_STATUS_ACTIVE, PDO::PARAM_STR);
            
            if ($categoryId) {
                $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
            }
            
            if ($limit) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log("Get active items error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get item by ID
     */
    public function getItemById($itemId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT i.*, c.category_name, c.slug as category_slug,
                       u.full_name as created_by_name
                FROM items i
                JOIN categories c ON i.category_id = c.category_id
                JOIN users u ON i.created_by = u.user_id
                WHERE i.item_id = :item_id
            ");
            
            $stmt->execute(['item_id' => $itemId]);
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            error_log("Get item by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get item by slug
     */
    public function getItemBySlug($slug) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT i.*, c.category_name, c.slug as category_slug,
                       u.full_name as created_by_name
                FROM items i
                JOIN categories c ON i.category_id = c.category_id
                JOIN users u ON i.created_by = u.user_id
                WHERE i.slug = :slug AND i.status = :status
            ");
            
            $stmt->execute([
                'slug' => $slug,
                'status' => ITEM_STATUS_ACTIVE
            ]);
            
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            error_log("Get item by slug error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Search items
     */
    public function searchItems($query, $limit = 20, $offset = 0) {
        try {
            $searchTerm = "%{$query}%";
            
            $stmt = $this->pdo->prepare("
                SELECT i.*, c.category_name, c.slug as category_slug
                FROM items i
                JOIN categories c ON i.category_id = c.category_id
                WHERE i.status = :status
                AND (i.item_name LIKE :search OR i.description LIKE :search)
                ORDER BY i.item_name ASC
                LIMIT :limit OFFSET :offset
            ");
            
            $stmt->bindValue(':status', ITEM_STATUS_ACTIVE, PDO::PARAM_STR);
            $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log("Search items error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get item price history
     */
    public function getPriceHistory($itemId, $limit = 30) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT ph.*, u.full_name as updated_by_name
                FROM price_history ph
                JOIN users u ON ph.updated_by = u.user_id
                WHERE ph.item_id = :item_id
                ORDER BY ph.updated_at DESC
                LIMIT :limit
            ");
            
            $stmt->bindValue(':item_id', $itemId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log("Get price history error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get items with significant price changes
     */
    public function getSignificantPriceChanges($threshold = PRICE_CHANGE_THRESHOLD, $limit = 10) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT i.item_id, i.item_name, i.current_price, i.image_path, i.slug,
                       ph.old_price, ph.new_price, ph.price_change_percent, ph.updated_at
                FROM items i
                JOIN price_history ph ON i.item_id = ph.item_id
                WHERE ABS(ph.price_change_percent) >= :threshold
                AND ph.updated_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                ORDER BY ABS(ph.price_change_percent) DESC
                LIMIT :limit
            ");
            
            $stmt->bindValue(':threshold', $threshold, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log("Get significant price changes error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get item tags
     */
    public function getItemTags($itemId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT t.*
                FROM tags t
                JOIN item_tags it ON t.tag_id = it.tag_id
                WHERE it.item_id = :item_id
            ");
            
            $stmt->execute(['item_id' => $itemId]);
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log("Get item tags error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Generate unique slug from item name
     */
    public function generateSlug($name) {
        // Convert to lowercase and replace spaces with hyphens
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        
        // Check if slug exists
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Check if slug exists
     */
    private function slugExists($slug) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM items WHERE slug = :slug");
            $stmt->execute(['slug' => $slug]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Slug exists check error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Count total items
     */
    public function countItems($categoryId = null, $status = ITEM_STATUS_ACTIVE) {
        try {
            $sql = "SELECT COUNT(*) FROM items WHERE status = :status";
            
            if ($categoryId) {
                $sql .= " AND category_id = :category_id";
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            
            if ($categoryId) {
                $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetchColumn();
            
        } catch (PDOException $e) {
            error_log("Count items error: " . $e->getMessage());
            return 0;
        }
    }
}

?>
