<?php
$databasePath = __DIR__ . '/../database.sqlite';

try {
    $conn = new PDO("sqlite:$databasePath");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 'users' table schema
    $conn->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            role TEXT NOT NULL DEFAULT 'user',
            photo TEXT DEFAULT NULL,
            category_id INTEGER DEFAULT NULL,
            subcategory_id INTEGER DEFAULT NULL,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
            FOREIGN KEY (subcategory_id) REFERENCES subcategories(id) ON DELETE SET NULL
        )
    ");

    // 'categories' table schema
    $conn->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // 'subcategories' table schema
    $conn->exec("
        CREATE TABLE IF NOT EXISTS subcategories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            category_id INTEGER NOT NULL,
            name TEXT NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
        )
    ");

    
     $checkColumns = $conn->query("PRAGMA table_info(users)")->fetchAll();
     $columns = array_column($checkColumns, 'name');
 
     
     if (!in_array('category_id', $columns)) {
         $conn->exec("ALTER TABLE users ADD COLUMN category_id INTEGER DEFAULT NULL");
     }
     if (!in_array('subcategory_id', $columns)) {
         $conn->exec("ALTER TABLE users ADD COLUMN subcategory_id INTEGER DEFAULT NULL");
     }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
