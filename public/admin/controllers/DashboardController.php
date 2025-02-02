<?php
/*
namespace Admin\Controllers;

require_once __DIR__ . '/../models/Module.php';
use Admin\Models\Module;

class DashboardController {
    public function index() {
        // Pobranie listy aktywnych modułów
        $modules = Module::getActiveModules();

        // Przekazanie modułów do widoku
        require __DIR__ . '/../views/dashboard/index.php';
    }
}*/

namespace Admin\Controllers;

require_once __DIR__ . '/../models/Module.php';
use Admin\Models\Module;

class DashboardController {
    private $theme;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Pobranie aktywnego motywu z bazy danych lub ustawienie domyślnego
        $this->theme = $this->getActiveTheme();
    }

    public function index() {
        // Pobranie listy aktywnych modułów
        $modules = Module::getActiveModules();

        // Załadowanie widoku w kontekście wybranego motywu
        $content = $this->renderView('dashboard', ['modules' => $modules]);
        require __DIR__ . "/../themes/{$this->theme}/layouts/main.php";
    }

    private function renderView($view, $data = []) {
        // Renderowanie widoku
        extract($data);
        ob_start();
        include __DIR__ . "/../themes/{$this->theme}/views/{$view}.php";
        return ob_get_clean();
    }

    private function getActiveTheme() {
        // Pobranie aktywnego motywu z tabeli admin_themes
        $db = \App\Database::getConnection();
        $stmt = $db->query("SELECT directory FROM admin_themes WHERE is_active = 1 LIMIT 1");
        $theme = $stmt->fetchColumn();

        // Jeśli brak zdefiniowanego motywu, zwracamy domyślny
        return $theme ?: 'default';
    }
    public function logout() {
        session_start();
        session_unset(); // Usuń wszystkie dane z sesji
        session_destroy(); // Zniszcz sesję
        header('Location: /admin/login');
        exit;
    }
    
}


