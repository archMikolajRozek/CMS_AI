<?php

namespace Admin\Controllers;

use Admin\Models\Module;
use Admin\Models\Language;

class SettingsController {
    /**
     * Główna strona ustawień.
     */
    public function index() {
        require __DIR__ . '/../views/settings/index.php';
    }

    /**
     * Zarządzanie modułami.
     */
    public function modules() {
        $modules = Module::getAll();
        require __DIR__ . '/../views/settings/modules.php';
    }

    /**
     * Akcje na modułach.
     */
    public function toggleModule($id, $action) {
        $module = Module::find($id);

        if (!$module) {
            die('Module not found.');
        }

        if (in_array($module->name, ['Użytkownicy', 'Moduły', 'Ustawienia'])) {
            die('Cannot modify essential modules.');
        }

        switch ($action) {
            case 'activate':
                $module->activate();
                break;
            case 'deactivate':
                $module->deactivate();
                break;
            case 'freeze':
                $module->freeze();
                break;
            default:
                die('Invalid action.');
        }

        header('Location: /admin/settings/modules');
        exit;
    }


    /**
     * Akcje na jezykach.
     */
    public function languages() {
        $languages = Language::getAll();
        require __DIR__ . '/../views/settings/languages.php';
    }

    public function toggleLanguage($id, $action) {
        $language = Language::find($id);
    
        if (!$language) {
            die('Language not found.');
        }
    //var_dump($action);
        switch ($action) {
            case 'freeze':
                $language->freeze();
                break;
            case 'activate':
                $language->activate();
                break;
            case 'delete':
                $language->delete();
                break;
            case 'set-default':
                Language::setDefault($id);
                break;
            default:
                die('Invalid action.');
        }
    
        header('Location: /admin/settings/languages');
        exit;
    }

    public function addLanguage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code']);
            $name = trim($_POST['name']);

            if (empty($code) || empty($name)) {
                die('Language code and name are required.');
            }

            Language::create([
                'code' => $code,
                'name' => $name
            ]);

            header('Location: /admin/settings/languages');
            exit;
        }

        require __DIR__ . '/../views/settings/add_language.php';
    }

    public function setDefaultLanguage($id) {
        // Debugowanie
        error_log("ID języka: " . $id);
    
        $language = Language::find($id);
    
        if (!$language) {
            error_log("Nie znaleziono języka o ID: " . $id);
            die('Language not found.');
        }
    
        Language::setDefault($id);
    
        header('Location: /admin/settings/languages');
        exit;
    }

    /**
     * Koniec akcji na jezykach
     */



}
