<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = $_POST['db_host'];
    $dbUser = $_POST['db_user'];
    $dbPass = $_POST['db_pass'];
    $dbName = $_POST['db_name'];

    // Połączenie z bazą danych
    $mysqli = new mysqli($dbHost, $dbUser, $dbPass);
    if ($mysqli->connect_error) {
        die('Database connection failed: ' . $mysqli->connect_error);
    }

    // Tworzenie bazy danych
    if (!$mysqli->query("CREATE DATABASE IF NOT EXISTS `$dbName`")) {
        die('Failed to create database: ' . $mysqli->error);
    }

    $mysqli->select_db($dbName);

    // Tworzenie tabel
    $tables = [
                "users" => "CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(255) UNIQUE NOT NULL,
                    email VARCHAR(255) UNIQUE NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    role_id INT NOT NULL,
                    is_active TINYINT(1) DEFAULT 1,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )",
                "roles" => "CREATE TABLE IF NOT EXISTS roles (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) UNIQUE NOT NULL,
                    permissions TEXT NOT NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )",
                "permissions" => "CREATE TABLE IF NOT EXISTS permissions (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    module VARCHAR(255) NOT NULL,
                    action ENUM('add', 'edit', 'delete', 'publish') NOT NULL
                )",
                "modules" => "CREATE TABLE IF NOT EXISTS modules (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    description TEXT,
                    status ENUM('active', 'inactive', 'frozen') DEFAULT 'active',
                    is_builtin TINYINT(1) DEFAULT 0,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )",
                "languages" => "CREATE TABLE IF NOT EXISTS languages (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    code VARCHAR(10) UNIQUE NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    status ENUM('active', 'frozen') DEFAULT 'active',
                    is_default TINYINT(1) DEFAULT 0,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )",
                "backups" => "CREATE TABLE IF NOT EXISTS backups (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    file_name VARCHAR(255) NOT NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )",
                "themes" => "CREATE TABLE IF NOT EXISTS themes (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    type ENUM('frontend', 'admin') NOT NULL,
                    status ENUM('active', 'inactive') DEFAULT 'inactive',
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )",
                "logs" => "CREATE TABLE IF NOT EXISTS logs (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT,
                    module VARCHAR(255),
                    action VARCHAR(255),
                    details TEXT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                )",
                "admin_themes" => "CREATE TABLE IF NOT EXISTS admin_themes (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    directory VARCHAR(255) NOT NULL,
                    description TEXT,
                    version VARCHAR(50),
                    author VARCHAR(255),
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    is_active TINYINT(1) DEFAULT 0
                )",
                "frontend_themes" => "CREATE TABLE IF NOT EXISTS frontend_themes (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    directory VARCHAR(255) NOT NULL,
                    description TEXT,
                    version VARCHAR(50),
                    author VARCHAR(255),
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    is_active TINYINT(1) DEFAULT 0
                )",

            ];

            foreach ($tables as $name => $query) {
                if (!$mysqli->query($query)) {
                    die("Failed to create table `$name`: " . $mysqli->error);
                }
            }

            // Dodanie domyślnych modułów, tylko jeśli tabela jest pusta
            $result = $mysqli->query("SELECT COUNT(*) as count FROM modules");
            $row = $result->fetch_assoc();
            if ($row['count'] == 0) {
                $modules = [
                    ['Ustawienia', 'Konfiguracja systemu CMS', 1],
                    ['Użytkownicy', 'Zarządzanie użytkownikami i rolami', 1],
                    ['Konfiguracja Bloków strony', 'Edycja i zarządzanie blokami strony', 1],
                    ['Pomoc', 'Pomoc i dokumentacja systemu', 1],
                    ['Aktualności', 'Zarządzanie treściami aktualności', 0],
                    ['Galeria', 'Zarządzanie galerią zdjęć i albumami', 0],
                    ['Podstrony', 'Tworzenie i zarządzanie podstronami', 0],
                    ['Menu', 'Zarządzanie menu nawigacyjnym', 0],
                    ['Kontakt', 'Obsługa formularza kontaktowego', 0],
                    ['Newsletter', 'Obsługa zapisów na newsletter', 0]
                ];

                foreach ($modules as $module) {
                    $stmt = $mysqli->prepare("INSERT INTO modules (name, description, is_builtin) VALUES (?, ?, ?)");
                    $stmt->bind_param("ssi", $module[0], $module[1], $module[2]);
                    $stmt->execute();
                }
            }

            // Dodanie domyślnych ról, tylko jeśli tabela jest pusta
            $result = $mysqli->query("SELECT COUNT(*) as count FROM roles");
            $row = $result->fetch_assoc();
            if ($row['count'] == 0) {
                $roles = [
                    ['name' => 'Admin', 'permissions' => json_encode(['all'])],
                    ['name' => 'Editor', 'permissions' => json_encode(['edit', 'add', 'publish'])],
                    ['name' => 'User', 'permissions' => json_encode(['view'])]
                ];

                foreach ($roles as $role) {
                    $stmt = $mysqli->prepare("INSERT INTO roles (name, permissions) VALUES (?, ?)");
                    $stmt->bind_param("ss", $role['name'], $role['permissions']);
                    $stmt->execute();
                }
            }

            // Dodanie Super Admina, tylko jeśli tabela użytkowników jest pusta
            $result = $mysqli->query("SELECT COUNT(*) as count FROM users");
            $row = $result->fetch_assoc();
            if ($row['count'] == 0) {
                $adminPassword = password_hash('cms4Eone$', PASSWORD_BCRYPT);
                $stmt = $mysqli->prepare("INSERT INTO users (username, email, password, role_id) VALUES ('admin', 'admin@cms4everyone.local', ?, 1)");
                $stmt->bind_param("s", $adminPassword);
                $stmt->execute();
            }
            
            // Dodanie domyślnych wpisów dla motywów admin i frontend, jeśli brak
            $result = $mysqli->query("SELECT COUNT(*) as count FROM admin_themes");
            $row = $result->fetch_assoc();
            if ($row['count'] == 0) {
                $stmt = $mysqli->prepare("INSERT INTO admin_themes (name, directory, description, version, author, is_active) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssi", $name, $directory, $description, $version, $author, $is_active);

                $themes = [
                    ['Default Admin Theme', 'default', 'Domyślny motyw panelu administracyjnego.', '1.0', 'CMS4Everyone Team', 1]
                ];

                foreach ($themes as $theme) {
                    [$name, $directory, $description, $version, $author, $is_active] = $theme;
                    $stmt->execute();
                }
            }

            $result = $mysqli->query("SELECT COUNT(*) as count FROM frontend_themes");
            $row = $result->fetch_assoc();
            if ($row['count'] == 0) {
                $stmt = $mysqli->prepare("INSERT INTO frontend_themes (name, directory, description, version, author, is_active) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssi", $name, $directory, $description, $version, $author, $is_active);

                $themes = [
                    ['Default Frontend Theme', 'default', 'Domyślny motyw strony frontendowej.', '1.0', 'CMS4Everyone Team', 1]
                ];

                foreach ($themes as $theme) {
                    [$name, $directory, $description, $version, $author, $is_active] = $theme;
                    $stmt->execute();
                }
            }

            $result = $mysqli->query("SELECT COUNT(*) as count FROM languages");
            $row = $result->fetch_assoc();

            if ($row['count'] == 0) {
                $languages = [
                    ['code' => 'pl', 'name' => 'Polski', 'status' => 'active', 'is_default' => 1],
                    ['code' => 'en', 'name' => 'English', 'status' => 'active', 'is_default' => 0]
                ];

                foreach ($languages as $language) {
                    $stmt = $mysqli->prepare("INSERT INTO languages (code, name, status, is_default) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("sssi", $language['code'], $language['name'], $language['status'], $language['is_default']);
                    $stmt->execute();
                }
            }

    // Zapis danych konfiguracyjnych
    $configContent = "<?php return [
        'db_host' => '$dbHost',
        'db_user' => '$dbUser',
        'db_pass' => '$dbPass',
        'db_name' => '$dbName',
    ];";
    file_put_contents(__DIR__ . '/../config/database.php', $configContent);

    // Przekierowanie do ekranu logowania
    header('Location: /admin/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Install CMS4Everyone</title>
</head>
<body>
    <h1>Install CMS4Everyone</h1>
    <form method="POST">
        <label>Database Host: <input type="text" name="db_host" required></label><br>
        <label>Database User: <input type="text" name="db_user" required></label><br>
        <label>Database Password: <input type="password" name="db_pass"></label><br>
        <label>Database Name: <input type="text" name="db_name" required></label><br>
        <button type="submit">Install</button>
    </form>
</body>
</html>
