<?php

namespace Admin\Controllers;

require_once __DIR__ . '/../models/Contact.php';
require_once __DIR__ . '/../models/Language.php';
use Admin\Models\Contact;
use Admin\Models\Language;

class ContactController {
    public function index() {
        $settings = Contact::getSettings();
        $formSettings = Contact::getFormSettings();
        $languages = Language::getAll();
        $translations = Contact::getTranslations();
        require __DIR__ . '/../views/contact/index.php';
    }

    public function saveSettings() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'address' => trim($_POST['address']),
                'phone_numbers' => json_encode(array_map('trim', explode(',', $_POST['phone_numbers']))),
                'working_hours' => json_encode(array_map('trim', explode(',', $_POST['working_hours']))),
                'social_links' => json_encode(array_map('trim', explode(',', $_POST['social_links']))),
                'map_iframe' => trim($_POST['map_iframe']),
                'translations' => $_POST['translations']
            ];

            Contact::saveSettings($data);
            header('Location: /admin/contact');
            exit;
        }
    }

    public function saveFormSettings() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'email' => trim($_POST['email']),
                'recaptcha_key' => trim($_POST['recaptcha_key']),
                'recaptcha_secret' => trim($_POST['recaptcha_secret'])
            ];

            Contact::saveFormSettings($data);
            header('Location: /admin/contact');
            exit;
        }
    }
}
