CREATE DATABASE IF NOT EXISTS lab11_db;
USE lab11_db;

-- Drop table if exists
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role VARCHAR(50) DEFAULT 'User',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample users
-- Passwords:
-- admin: admin123 (hashed: $2y$10$e0MYzXyjpJS7Pd0RVvHwHeTz6Fm/5G4zF9uK9H7x4yYhP8N4R7sC6)
-- ayush: password123 (hashed: $2y$10$wT5a8v9mKzB7yq8y8v7sbeD9Qz0K7G4zF9uK9H7x4yYhP8N4R7sC6)
-- student: student@2026 (hashed: $2y$10$j8Q1.j2vK3L4M5N6O7P8Q.u9R0S1T2U3V4W5X6Y7Z8a9b0c1d2e3f)

INSERT INTO users (username, password, full_name, email, role) VALUES
('admin', 'admin123', 'System Administrator', 'admin@example.com', 'Administrator'),
('ayush', 'password123', 'Ayush Kumar', 'ayushmessi2006@gmail.com', 'Developer'),
('student', 'student@2026', 'Engineering Student', 'student@university.edu', 'Student');

