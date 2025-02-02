<?

namespace Admin\Controllers;

class VersionController {
    public function index() {
        $version = json_decode(file_get_contents(__DIR__ . '/../../../composer.json'), true)['version'] ?? 'N/A';
        require __DIR__ . '/../views/version/index.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['version'])) {
            $composer = json_decode(file_get_contents(__DIR__ . '/../../../composer.json'), true);
            $composer['version'] = $_POST['version'];
            file_put_contents(__DIR__ . '/../../../composer.json', json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            header('Location: /admin/settings/version');
        }
    }
}

