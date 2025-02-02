<?

namespace Admin\Models;

use App\Database;

class Log {
    public static function log($userId, $module, $action, $details) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO logs (user_id, module, action, details) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $module, $action, $details]);
    }

    public static function getAll() {
        $db = Database::getConnection();
        return $db->query("SELECT * FROM logs ORDER BY created_at DESC")->fetchAll();
    }

    public static function toggleLogging($enabled) {
        // Możesz zapisać ten stan np. w tabeli ustawień
    }
}
