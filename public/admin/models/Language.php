<?php

namespace Admin\Models;

require_once __DIR__ . '/../../../vendor/autoload.php'; // Autoloader Composer

use App\Database;

class Language {
    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM languages ORDER BY name ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getActiveLanguages() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM languages WHERE status = 'active' ORDER BY name ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getDefaultLanguage() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM languages WHERE is_default = 1 LIMIT 1");
        $defaultLanguage = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$defaultLanguage) {
            die('Domyślny język nie został ustawiony.');
        }

        return $defaultLanguage;
    }

    public static function find($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM languages WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO languages (code, name) VALUES (?, ?)");
        return $stmt->execute([$data['code'], $data['name']]);
    }

    public function activate() {
        $this->updateStatus('active');
    }

    public function freeze() {
        $this->updateStatus('frozen');
    }

    public function delete() {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM languages WHERE id = ?");
        $stmt->execute([$this->id]);
    }

    public static function setDefault($id) {
        $db = Database::getConnection();

        // Resetowanie obecnego domyślnego języka
        $db->exec("UPDATE languages SET is_default = 0 WHERE is_default = 1");

        // Ustawienie nowego domyślnego języka
        $stmt = $db->prepare("UPDATE languages SET is_default = 1 WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function updateStatus($status) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE languages SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$status, $this->id]);
    }
}
