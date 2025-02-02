<?

namespace Admin\Controllers;

use Admin\Models\Log;

class LogsController {
    public function index() {
        $logs = Log::getAll();
        require __DIR__ . '/../views/logs/index.php';
    }

    public function toggleLogging() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Log::toggleLogging($_POST['enabled'] === '1');
            header('Location: /admin/settings/logs');
        }
    }
}
