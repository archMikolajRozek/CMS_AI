<?php

namespace Admin\Controllers;

use Admin\Models\News;
use Admin\Models\Language;

class NewsController {
    public function index() {
        $newsList = News::getAll();
        require __DIR__ . '/../views/news/index.php';
    }

    public function create() {
        $albums = News::getGalleryAlbums();
        $languages = Language::getActiveLanguages();
        $defaultLanguage = Language::getDefaultLanguage();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['id'] ?? null; // Pobierz ID użytkownika z sesji
    
            if (!$userId) {
                die('Błąd: Brak zalogowanego użytkownika.');
            }
    
            $_POST['created_by'] = $userId; // Dodaj ID użytkownika do danych POST
    
            // Obsługa wgrywania miniaturki
            if (!empty($_FILES['main_image']['name'])) {
                $uploadDir = __DIR__ . '/../../../uploads/news/';
                $_POST['main_image'] = $this->uploadImage($_FILES['main_image'], $uploadDir);
            }
    
            // Uzupełnij puste pola SEO na podstawie tytułu w domyślnym języku
            foreach ($languages as $language) {
                $langCode = $language['code'];
                if (empty($_POST['translations'][$langCode]['meta_title'])) {
                    $_POST['translations'][$langCode]['meta_title'] = $_POST['translations'][$langCode]['title'] ?? '';
                }
            }
    
            News::create($_POST);
            header('Location: /admin/news');
            exit;
        }
    
        require __DIR__ . '/../views/news/create.php';
    }

    public function edit($id) {
        $news = News::find($id);
        if (!$news) {
            die('Nie znaleziono aktualności.');
        }
    
        $albums = News::getGalleryAlbums();
        $translations = $news['translations'];
        $languages = Language::getActiveLanguages();
        $defaultLanguage = Language::getDefaultLanguage();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obsługa wgrywania miniaturki
            $mainImage = $news['main_image']; // Zachowaj istniejący obrazek jako domyślny
            if (!empty($_FILES['main_image']['name'])) {
                $uploadDir = __DIR__ . '/../../../uploads/news/';
                $mainImage = $this->uploadImage($_FILES['main_image'], $uploadDir);
            }
    
            // Przypisz dane POST
            $postData = $_POST;
            $postData['main_image'] = $mainImage;
    
            // Uzupełnij puste pola SEO na podstawie tytułu w domyślnym języku
            foreach ($languages as $language) {
                $langCode = $language['code'];
                if (empty($postData['translations'][$langCode]['meta_title'])) {
                    $postData['translations'][$langCode]['meta_title'] = $postData['translations'][$langCode]['title'] ?? '';
                }
            }
    
            News::update($id, $postData);
            header('Location: /admin/news');
            exit;
        }
    
        require __DIR__ . '/../views/news/edit.php';
    }
    

    public function delete($id) {
        News::delete($id);
        header('Location: /admin/news');
        exit;
    }

    public function toggleStatus($id) {
        News::toggleStatus($id);
        header('Location: /admin/news');
        exit;
    }

    private function uploadImage($file, $uploadDir) {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    
        $tmpName = $file['tmp_name'];
        $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
        $webpPath = $uploadDir . $originalName . '.webp';
    
        // Katalog dla miniaturek
        $thumbnailDir = $uploadDir . 'thumbnails/';
        if (!is_dir($thumbnailDir)) {
            mkdir($thumbnailDir, 0777, true);
        }
        $thumbnailPath = $thumbnailDir . $originalName . '.webp';
    
        // Konwersja do formatu .webp
        if (!$tmpName || !is_uploaded_file($tmpName)) {
            die('Nie można przesłać pliku. Sprawdź uprawnienia.');
        }
    
        $image = @imagecreatefromstring(file_get_contents($tmpName));
        if (!$image) {
            die('Nie można otworzyć obrazu. Sprawdź, czy plik jest poprawny.');
        }
    
        imagewebp($image, $webpPath);
    
        // Generowanie miniaturki
        $thumbnail = imagescale($image, 200); // 200px szerokości
        imagewebp($thumbnail, $thumbnailPath);
    
        // Czyszczenie pamięci
        imagedestroy($image);
        imagedestroy($thumbnail);
    
        return str_replace(__DIR__ . '/../../../', '/', $webpPath);
    }
    
}
