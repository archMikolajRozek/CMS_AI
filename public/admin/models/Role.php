<?php

namespace Admin\Models;

use App\Database;

class Role {
    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM roles");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM roles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
