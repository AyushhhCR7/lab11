# Lab 11: User Validation Web Application

A full-stack user authentication and validation web application. The application takes a **Login Name (Username)** and **Password** submitted by the user via an HTTP `POST` form, securely queries and verifies the credentials against records in the database, and returns a personalized **Successful Login Page** upon match or an informative **Failure Message Page** when credentials do not match.

---

## Table of Contents
1. [Problem Statement](#problem-statement)
2. [Architecture & Workflow](#architecture--workflow)
3. [Key Features](#key-features)
4. [Project Structure](#project-structure)
5. [Database Schema & Seed Data](#database-schema--seed-data)
6. [How to Run](#how-to-run)
   - [Option A: Built-in Node.js Server (Recommended - Zero Setup)](#option-a-built-in-nodejs-server-recommended---zero-setup)
   - [Option B: PHP Built-in Server](#option-b-php-built-in-server)
   - [Option C: Apache / XAMPP / WAMP](#option-c-apache--xampp--wamp)
7. [Security & Best Practices Implemented](#security--best-practices-implemented)
8. [Test Cases & Expected Results](#test-cases--expected-results)

---

## Problem Statement

> *"A user validation web application, where the user submits the login name & password to the server. The name and password are checked against the data already available in Database and if the data matches, a successful login page is returned. Otherwise failure message is shown to the user."*

---

## Architecture & Workflow

```
+-------------------------------------------------------------------------+
|                               Client Browser                            |
+-------------------------------------------------------------------------+
       |                                                 ^
       | 1. POST /login.php                              | 4. Returns
       |    (username & password)                        |    success.php OR failure.php
       v                                                 |
+-------------------------------------------------------------------------+
|                         Server-side Controller                          |
|                       (login.php / server.js)                           |
+-------------------------------------------------------------------------+
       |                                                 ^
       | 2. Prepared Query                               | 3. Returns Row Data
       |    SELECT * FROM users WHERE username = ?       |    (or Empty Result)
       v                                                 |
+-------------------------------------------------------------------------+
|                          Database Layer                                 |
|                       (SQLite / MySQL Database)                         |
+-------------------------------------------------------------------------+
```

---

## Key Features

- **Modern Glassmorphic UI**: Sleek, fully responsive design with custom ambient glows, high contrast, and accessible typography.
- **Robust Database Verification**: Uses parameterized prepared statements (`PDO` / SQLite) preventing SQL Injection vulnerabilities.
- **Dual-Mode Backend Support**:
  - **PHP (Industry / Academic Standard)**: Pure PHP + PDO with seamless auto-fallback between SQLite and MySQL.
  - **Node.js**: Native zero-dependency HTTP + `node:sqlite` server for instant local preview without requiring LAMP/WAMP.
- **Success & Failure Branching**:
  - **Success (`success.php`)**: Verified authentication badge, user greeting, profile card with User ID, Username, Email, Designation, and Session timestamp, plus Session Termination (`Logout`).
  - **Failure (`failure.php`)**: Animated error icon, explicit failure breakdown, troubleshooting recommendations, and one-click return navigation.
- **Interactive Testing Helper**: Instant credential chip buttons in the UI for rapid grading and demonstration.
- **Password Visibility Toggle**: Interactive show/hide toggle for password input.

---

## Project Structure

```text
lab11/
├── index.html          # Client login page with form and interactive test chips
├── style.css           # Modern CSS3 stylesheet (glassmorphism, animations, responsive)
├── db.php              # Database connection with dual MySQL & SQLite PDO support
├── login.php           # PHP backend controller checking credentials against database
├── success.php         # Successful login landing page showing user profile & session
├── failure.php         # Dedicated failure view displaying error diagnosis & try-again
├── logout.php          # Session termination script clearing cookies and session
├── schema.sql          # Standard SQL schema for MySQL/MariaDB database setup
├── server.js           # Built-in zero-dependency Node.js runner (HTTP + SQLite)
├── package.json        # NPM project configuration
├── .gitignore          # Git exclusion rules
└── README.md           # Comprehensive project documentation
```

---

## Database Schema & Seed Data

The database contains a `users` table structured as follows:

```sql
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role VARCHAR(50) DEFAULT 'User',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Pre-configured Sample Test Accounts

| Username (`Login Name`) | Password | Full Name | Role | Email |
|-------------------------|----------|-----------|------|-------|
| `ayush` | `password123` | Ayush Kumar | Developer | `ayushmessi2006@gmail.com` |
| `admin` | `admin123` | System Administrator | Administrator | `admin@example.com` |
| `student` | `student@2026` | Engineering Student | Student | `student@university.edu` |

---

## How to Run

### Option A: Built-in Node.js Server (Recommended - Zero Setup)

Works immediately on any computer with Node.js 22+ installed (no external npm dependencies required):

```bash
# Navigate to the project directory
cd /path/to/lab11

# Start the server
npm start
# or: node server.js
```

Open your browser and navigate to:
👉 **[http://localhost:8000](http://localhost:8000)**

---

### Option B: PHP Built-in Server

If PHP is installed on your workstation:

```bash
cd /path/to/lab11
php -S localhost:8000
```

Open **[http://localhost:8000](http://localhost:8000)** in your browser. The included `db.php` automatically provisions the SQLite database with seed data if MySQL is not running!

---

### Option C: Apache / XAMPP / WAMP

1. Copy the `lab11` folder into your web server's document root:
   - **XAMPP (Windows)**: `C:\xampp\htdocs\lab11`
   - **XAMPP (Linux)**: `/opt/lampp/htdocs/lab11`
   - **Apache (Linux)**: `/var/www/html/lab11`
2. *(Optional MySQL Setup)*: Open phpMyAdmin or MySQL CLI and run `schema.sql`:
   ```bash
   mysql -u root -p < schema.sql
   ```
3. Set `$db_driver = 'mysql'` in `db.php` if using MySQL, or leave it as default `'sqlite'` for automated file-based DB.
4. Access the web application at:
   👉 **[http://localhost/lab11/](http://localhost/lab11/)**

---

## Security & Best Practices Implemented

1. **Prepared Statements**: User inputs are bound via parameterized queries, preventing **SQL Injection (SQLi)** attacks.
2. **Session Hijacking Mitigation**: Uses `session_regenerate_id(true)` upon successful validation to prevent session fixation.
3. **Cross-Site Scripting (XSS) Prevention**: All dynamic user-submitted outputs rendered in HTML are sanitized via `htmlspecialchars()`.
4. **Clean Session Destruction**: Complete logout flow clearing `$_SESSION`, expiring session cookies, and calling `session_destroy()`.

---

## Test Cases & Expected Results

| # | Test Scenario | Input Credentials | Expected Server Response |
|---|---------------|-------------------|--------------------------|
| 1 | Valid Developer Login | `ayush` / `password123` | Displays **Login Successful!** page with profile details for Ayush Kumar |
| 2 | Valid Admin Login | `admin` / `admin123` | Displays **Login Successful!** page with Administrator role |
| 3 | Valid Student Login | `student` / `student@2026` | Displays **Login Successful!** page with Student details |
| 4 | Invalid Username | `invalid_user` / `password123` | Displays **Authentication Failed** page with error explanation |
| 5 | Incorrect Password | `ayush` / `wrongpassword` | Displays **Authentication Failed** page with troubleshooting tips |
| 6 | Empty Form Submission | *(blank)* / *(blank)* | Displays validation error requiring all fields |
| 7 | Session Logout | Click **Logout** button on success page | Session destroyed, returns to `index.html` with logout confirmation |
