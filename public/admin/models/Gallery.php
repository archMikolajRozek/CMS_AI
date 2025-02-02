<?php

namespace Admin\Models;

use App\Database;

class Gallery {
    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM gallery");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO gallery (filename, title, description, created_at) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['filename'], $data['title'], $data['description'], date('Y-m-d H:i:s')]);
    }

    public static function delete($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
