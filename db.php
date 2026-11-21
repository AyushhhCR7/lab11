<?php
/**
 * Database Connection & Initialization Module
 * Supports both MySQL and auto-configured SQLite with prepared statements.
 */

// Configuration settings (Adjust for MySQL environment if desired)
$db_driver = 'sqlite'; // 'sqlite' or 'mysql'

$mysql_host = 'localhost';
$mysql_db   = 'lab11_db';
$mysql_user = 'root';
$mysql_pass = '';
$mysql_port = '3306';

$sqlite_file = __DIR__ . '/lab11.db';

$pdo = null;

try {
    if ($db_driver === 'mysql') {
        $dsn = "mysql:host=$mysql_host;port=$mysql_port;dbname=$mysql_db;charset=utf8mb4";
        $pdo = new PDO($dsn, $mysql_user, $mysql_pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } else {
        // Default to SQLite for zero-setup portability
        $is_new_db = !file_exists($sqlite_file);
        $pdo = new PDO("sqlite:" . $sqlite_file);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // Auto-initialize SQLite table and seed data if newly created
        if ($is_new_db || filesize($sqlite_file) === 0) {
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS users (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    username TEXT NOT NULL UNIQUE,
                    password TEXT NOT NULL,
                    full_name TEXT NOT NULL,
                    email TEXT NOT NULL,
                    role TEXT DEFAULT 'User',
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                );
            ");

            // Seed default users (matching schema.sql)
            $seedUsers = [
                ['admin', 'admin123', 'System Administrator', 'admin@example.com', 'Administrator'],
                ['ayush', 'password123', 'Ayush Kumar', 'ayushmessi2006@gmail.com', 'Developer'],
                ['student', 'student@2026', 'Engineering Student', 'student@university.edu', 'Student']
            ];

            $insertStmt = $pdo->prepare("
                INSERT OR IGNORE INTO users (username, password, full_name, email, role)
                VALUES (?, ?, ?, ?, ?)
            ");

            foreach ($seedUsers as $u) {
                $insertStmt->execute($u);
            }
        }
    }
} catch (PDOException $e) {
    // If MySQL connection fails, seamlessly fallback to SQLite
    try {
        $pdo = new PDO("sqlite:" . $sqlite_file);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                full_name TEXT NOT NULL,
                email TEXT NOT NULL,
                role TEXT DEFAULT 'User',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        $seedUsers = [
            ['admin', 'admin123', 'System Administrator', 'admin@example.com', 'Administrator'],
            ['ayush', 'password123', 'Ayush Kumar', 'ayushmessi2006@gmail.com', 'Developer'],
            ['student', 'student@2026', 'Engineering Student', 'student@university.edu', 'Student']
        ];

        $insertStmt = $pdo->prepare("
            INSERT OR IGNORE INTO users (username, password, full_name, email, role)
            VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($seedUsers as $u) {
            $insertStmt->execute($u);
        }
    } catch (PDOException $ex) {
        die("Database connection failed: " . htmlspecialchars($ex->getMessage()));
    }
}
?>
