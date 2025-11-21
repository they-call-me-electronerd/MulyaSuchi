<?php
/**
 * Database Connection Class
 * 
 * Singleton pattern for database connections
 */

class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        global $pdo;
        if ($pdo === null) {
            require_once __DIR__ . '/../config/database.php';
        }
        $this->pdo = $GLOBALS['pdo'];
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    // Prevent cloning
    private function __clone() {}
    
    // Prevent unserialization
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

?>
