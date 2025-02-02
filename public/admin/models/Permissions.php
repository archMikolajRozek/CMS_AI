<?php

namespace Admin\Models;

use App\Database;

class Permission {
    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM permissions");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function findByModuleAndAction($module, $action) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM permissions WHERE module = ? AND action = ?");
        $stmt->execute([$module, $action]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
