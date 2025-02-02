<?php

namespace Admin\Models;

require_once __DIR__ . '/../../../vendor/autoload.php';
use App\Database;

class Module {
    public $id;
    public $name;
    public $description;
    public $status;
    public $is_builtin;

    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM modules ORDER BY is_builtin DESC, name ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM modules WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            $module = new self();
            $module->id = $data['id'];
            $module->name = $data['name'];
            $module->description = $data['description'];
            $module->status = $data['status'];
            $module->is_builtin = $data['is_builtin'];
            return $module;
        }

        return null;
    }

    public function activate() {
        $this->checkAndCreateOrUpdateTables();
        $this->updateStatus('active');
    }

    public function deactivate() {
        $this->updateStatus('inactive');
    }

    public function freeze() {
        $this->updateStatus('frozen');
    }

    private function updateStatus($status) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE modules SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$status, $this->id]);
        $this->status = $status;
    }
    
    
    public static function getActiveModules() {
        $db = Database::getConnection();

        // Pobranie modułów, które są aktywne (status = 'active')
        $stmt = $db->query("SELECT name, description FROM modules WHERE status = 'active'");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function checkAndCreateOrUpdateTables() {
        $db = Database::getConnection();
    
        // Definicje tabel dla poszczególnych modułów
        $tables = [
            'Aktualności' => [
                [
                    'table' => 'news',
                    'columns' => [
                        'id INT AUTO_INCREMENT PRIMARY KEY',
                        'gallery_album_id INT DEFAULT NULL',
                        'status ENUM("draft", "published", "scheduled") DEFAULT "draft"',
                        'scheduled_date DATETIME DEFAULT NULL',
                        'created_by INT NOT NULL',
                        'accepted_by INT DEFAULT NULL',
                        'published_by INT DEFAULT NULL',
                        'publish_date DATETIME DEFAULT NULL',
                        'main_image VARCHAR(255) DEFAULT NULL',
                        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
                        'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
                        'FOREIGN KEY (gallery_album_id) REFERENCES gallery_albums(id) ON DELETE SET NULL',
                        'FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE',
                        'FOREIGN KEY (accepted_by) REFERENCES users(id) ON DELETE SET NULL',
                        'FOREIGN KEY (published_by) REFERENCES users(id) ON DELETE SET NULL'
                    ]
                ],
                [
                    'table' => 'news_translations',
                    'columns' => [
                        'id INT AUTO_INCREMENT PRIMARY KEY',
                        'news_id INT NOT NULL',
                        'language_code VARCHAR(10) NOT NULL',
                        'title VARCHAR(255) NOT NULL',
                        'summary TEXT DEFAULT NULL',
                        'content TEXT',
                        'meta_title VARCHAR(255)',
                        'meta_description TEXT',
                        'meta_keywords TEXT',
                        'og_title VARCHAR(255)',
                        'og_description TEXT',
                        'og_image VARCHAR(255)',
                        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
                        'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
                        'FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE'
                    ]
                ]
            ],
            'Galeria' => [
                [
                    'table' => 'gallery',
                    'columns' => [
                        'id INT AUTO_INCREMENT PRIMARY KEY',
                        'filename VARCHAR(255) NOT NULL',
                        'album_id INT DEFAULT NULL',
                        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
                        'FOREIGN KEY (album_id) REFERENCES gallery_albums(id) ON DELETE SET NULL'
                    ]
                ],
                [
                    'table' => 'gallery_translations',
                    'columns' => [
                        'id INT AUTO_INCREMENT PRIMARY KEY',
                        'gallery_id INT NOT NULL',
                        'language_code VARCHAR(10) NOT NULL',
                        'title VARCHAR(255)',
                        'description TEXT',
                        'alt_text VARCHAR(255)',
                        'seo_title VARCHAR(255)',
                        'seo_description TEXT',
                        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
                        'FOREIGN KEY (gallery_id) REFERENCES gallery(id) ON DELETE CASCADE'
                    ]
                ],
                [
                    'table' => 'gallery_albums',
                    'columns' => [
                        'id INT AUTO_INCREMENT PRIMARY KEY',
                        'cover_image VARCHAR(255)',
                        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP'
                    ]
                ],
                [
                    'table' => 'gallery_album_translations',
                    'columns' => [
                        'id INT AUTO_INCREMENT PRIMARY KEY',
                        'album_id INT NOT NULL',
                        'language_code VARCHAR(10) NOT NULL',
                        'title VARCHAR(255)',
                        'description TEXT',
                        'slug VARCHAR(255) UNIQUE NOT NULL',
                        'meta_title VARCHAR(255)',
                        'meta_description TEXT',
                        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
                        'FOREIGN KEY (album_id) REFERENCES gallery_albums(id) ON DELETE CASCADE'
                    ]
                ]
            ],
            'Kontakt' => [
                [
                    'table' => 'contact_form_settings',
                    'columns' => [
                        'id INT AUTO_INCREMENT PRIMARY KEY',
                        'email VARCHAR(255) NOT NULL',
                        'recaptcha_key VARCHAR(255)',
                        'recaptcha_secret VARCHAR(255)',
                        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
                        'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
                    ]
                ],
                [
                    'table' => 'contact_messages',
                    'columns' => [
                        'id INT AUTO_INCREMENT PRIMARY KEY',
                        'name VARCHAR(255) NOT NULL',
                        'email VARCHAR(255) NOT NULL',
                        'message TEXT NOT NULL',
                        'phone VARCHAR(50)',
                        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
                        'status ENUM("new", "read") DEFAULT "new"'
                    ]
                ],
                [
                    'table' => 'contact_settings',
                    'columns' => [
                        'id INT AUTO_INCREMENT PRIMARY KEY',
                        'address TEXT',
                        'phone_numbers TEXT',
                        'working_hours TEXT',
                        'social_links TEXT',
                        'map_iframe TEXT',
                        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
                        'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
                    ]
                ],
                [
                    'table' => 'contact_translations',
                    'columns' => [
                        'id INT AUTO_INCREMENT PRIMARY KEY',
                        'contact_id INT NOT NULL',
                        'language_code VARCHAR(10) NOT NULL',
                        'address TEXT',
                        'working_hours TEXT',
                        'social_links TEXT',
                        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
                        'FOREIGN KEY (contact_id) REFERENCES contact_settings(id) ON DELETE CASCADE'
                    ]
                ]
            ],
            'Podstrony' => [
                [
                    'table' => 'pages',
                    'columns' => [
                        'id INT AUTO_INCREMENT PRIMARY KEY',
                        'slug VARCHAR(255) UNIQUE NOT NULL',
                        'gallery_album_id INT DEFAULT NULL',
                        'status ENUM("draft", "published") DEFAULT "draft"',
                        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
                        'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
                    ]
                ],
                [
                    'table' => 'page_translations',
                    'columns' => [
                        'id INT AUTO_INCREMENT PRIMARY KEY',
                        'page_id INT NOT NULL',
                        'language_code VARCHAR(10) NOT NULL',
                        'title VARCHAR(255) NOT NULL',
                        'content TEXT',
                        'meta_title VARCHAR(255)',
                        'meta_description TEXT',
                        'meta_keywords TEXT',
                        'og_title VARCHAR(255)',
                        'og_description TEXT',
                        'og_image VARCHAR(255)',
                        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
                        'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
                        'FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE'
                    ]
                ]
            ],


            ///////////////////////
        ];
    
        // Sprawdzenie, czy moduł ma przypisane tabele do utworzenia
        if (isset($tables[$this->name])) {
            foreach ($tables[$this->name] as $tableDefinition) {
                $table = $tableDefinition['table'];
                $expectedColumns = $tableDefinition['columns'];
    
                // Sprawdź, czy tabela istnieje
                $stmt = $db->query("SHOW TABLES LIKE '$table'");
                if ($stmt->rowCount() === 0) {
                    // Jeśli tabela nie istnieje, utwórz ją
                    $createQuery = "CREATE TABLE `$table` (" . implode(', ', $expectedColumns) . ")";
                    $db->exec($createQuery);
                } else {
                    // Jeśli tabela istnieje, zaktualizuj jej strukturę
                    $this->updateTableSchema($db, $table, $expectedColumns);
                }
            }
        }
    }
    
    private function updateTableSchema($db, $table, $expectedColumns) {
        // Pobierz istniejące kolumny tabeli
        $stmt = $db->query("DESCRIBE `$table`");
        $existingColumns = $stmt->fetchAll(\PDO::FETCH_COLUMN);
    
        // Porównanie kolumn i dodanie brakujących
        foreach ($expectedColumns as $columnDefinition) {
            $columnName = explode(' ', $columnDefinition)[0];
            if (!in_array($columnName, $existingColumns)) {
                $db->exec("ALTER TABLE `$table` ADD $columnDefinition");
            }
        }
    }
}
