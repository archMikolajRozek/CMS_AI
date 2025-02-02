<?php
namespace Admin\Models;

use App\Database;

class Contact {
    public static function getSettings() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM contact_settings LIMIT 1");
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getFormSettings() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM contact_form_settings LIMIT 1");
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getTranslations() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM contact_translations");
        $translations = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $result = [];
        foreach ($translations as $translation) {
            $result[$translation['language_code']] = $translation;
        }
        return $result;
    }

    public static function saveSettings($data) {
        $db = Database::getConnection();
    
        // Upewniamy się, że główny rekord istnieje
        $stmt = $db->prepare("INSERT IGNORE INTO contact_settings (id, address, phone_numbers, working_hours, social_links, map_iframe) 
                              VALUES (1, '', '[]', '[]', '[]', '')");
        $stmt->execute();
    
        // Aktualizacja głównych ustawień kontaktu
        $stmt = $db->prepare("UPDATE contact_settings SET 
            address = ?, 
            phone_numbers = ?, 
            working_hours = ?, 
            social_links = ?, 
            map_iframe = ?, 
            updated_at = NOW() 
            WHERE id = 1");
        $stmt->execute([
            $data['address'],
            $data['phone_numbers'],
            $data['working_hours'],
            $data['social_links'],
            $data['map_iframe']
        ]);
    
        // Aktualizacja tłumaczeń
        foreach ($data['translations'] as $languageCode => $translation) {
            $stmt = $db->prepare("INSERT INTO contact_translations 
                (contact_id, language_code, address, working_hours, social_links) 
                VALUES (1, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    address = VALUES(address), 
                    working_hours = VALUES(working_hours), 
                    social_links = VALUES(social_links)");
            $stmt->execute([
                $languageCode,
                $translation['address'],
                $translation['working_hours'],
                $translation['social_links']
            ]);
        }
    }
    

    public static function saveFormSettings($data) {
        $db = Database::getConnection();
    
        // Upewniamy się, że główny rekord istnieje
        $stmt = $db->prepare("INSERT IGNORE INTO contact_form_settings (id, email, recaptcha_key, recaptcha_secret) 
                              VALUES (1, '', '', '')");
        $stmt->execute();
    
        // Aktualizacja danych w tabeli
        $stmt = $db->prepare("UPDATE contact_form_settings SET 
            email = ?, 
            recaptcha_key = ?, 
            recaptcha_secret = ?, 
            updated_at = NOW() 
            WHERE id = 1");
        $stmt->execute([
            $data['email'],
            $data['recaptcha_key'],
            $data['recaptcha_secret']
        ]);
    }
    
}
