<?php

namespace Admin\Models;

use App\Database;

class News {
    public static function getAll() {
        $db = Database::getConnection();
    
        // Pobierz domyślny język (tylko kod języka)
        $defaultLanguage = Language::getDefaultLanguage(); 
    
        $query = "
            SELECT 
                n.id, 
                n.status, 
                n.scheduled_date, 
                n.publish_date, 
                n.created_by, 
                n.accepted_by, 
                n.published_by, 
                n.created_at, 
                nt.language_code, 
                nt.title, 
                u.username AS created_by_user, 
                ua.username AS accepted_by_user, 
                up.username AS published_by_user
            FROM news n
            LEFT JOIN news_translations nt ON n.id = nt.news_id AND nt.language_code = :default_language
            LEFT JOIN users u ON n.created_by = u.id
            LEFT JOIN users ua ON n.accepted_by = ua.id
            LEFT JOIN users up ON n.published_by = up.id
            ORDER BY n.created_at DESC
        ";
    
        try {
            $stmt = $db->prepare($query);
            $stmt->execute(['default_language' => $defaultLanguage['code']]);
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            error_log("Błąd zapytania SQL w News::getAll(): " . $e->getMessage());
            return [];
        }
    }
    
    public static function find($id) {
        $db = Database::getConnection();
        $query = "
            SELECT 
                n.id, 
                n.status, 
                n.scheduled_date, 
                n.publish_date, 
                n.created_by, 
                n.accepted_by, 
                n.published_by, 
                n.created_at, 
                n.updated_at, 
                n.gallery_album_id, 
                nt.language_code, 
                nt.title, 
                nt.summary, 
                nt.content, 
                nt.meta_title, 
                nt.meta_description, 
                nt.meta_keywords, 
                nt.og_title, 
                nt.og_description, 
                nt.og_image 
            FROM news n
            LEFT JOIN news_translations nt ON n.id = nt.news_id
            WHERE n.id = :id
        ";
        $stmt = $db->prepare($query);
        $stmt->execute(['id' => $id]);
        $news = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
        if (!$news) {
            return null;
        }
    
        // Przekształć dane w strukturę obsługującą tłumaczenia
        $result = [
            'id' => $news[0]['id'],
            'status' => $news[0]['status'],
            'scheduled_date' => $news[0]['scheduled_date'],
            'publish_date' => $news[0]['publish_date'],
            'created_by' => $news[0]['created_by'],
            'accepted_by' => $news[0]['accepted_by'],
            'published_by' => $news[0]['published_by'],
            'created_at' => $news[0]['created_at'],
            'updated_at' => $news[0]['updated_at'],
            'gallery_album_id' => $news[0]['gallery_album_id'],
            'translations' => []
        ];
    
        foreach ($news as $translation) {
            $result['translations'][$translation['language_code']] = [
                'title' => $translation['title'],
                'summary' => $translation['summary'],
                'content' => $translation['content'],
                'meta_title' => $translation['meta_title'],
                'meta_description' => $translation['meta_description'],
                'meta_keywords' => $translation['meta_keywords'],
                'og_title' => $translation['og_title'],
                'og_description' => $translation['og_description'],
                'og_image' => $translation['og_image']
            ];
        }
    
        return $result;
    }
    
    public static function create($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO news (status, main_image, gallery_album_id, created_at, created_by)
            VALUES (:status, :main_image, :gallery_album_id, NOW(), :created_by)
        ");
        $stmt->execute([
            ':status' => $data['status'],
            ':main_image' => $data['main_image'] ?? null,
            ':gallery_album_id' => $data['gallery_album_id'] ?? null,
            ':created_by' => $data['created_by']
        ]);
        $newsId = $db->lastInsertId();

        foreach ($data['translations'] as $languageCode => $translation) {
            $stmt = $db->prepare("
                INSERT INTO news_translations (news_id, language_code, title, summary, content)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $newsId,
                $languageCode,
                $translation['title'],
                $translation['summary'],
                $translation['content']
            ]);
        }
    }

    public static function update($id, $data) {
        $db = Database::getConnection();
    
        // Sprawdź poprawność `gallery_album_id`
        $galleryAlbumId = !empty($data['gallery_album_id']) ? (int)$data['gallery_album_id'] : null;
    
        $query = "
            UPDATE news 
            SET 
                gallery_album_id = :gallery_album_id,
                status = :status,
                scheduled_date = :scheduled_date,
                main_image = :main_image,
                updated_at = NOW()
            WHERE id = :id
        ";
    
        $stmt = $db->prepare($query);
        $stmt->execute([
            'gallery_album_id' => $galleryAlbumId,
            'status' => $data['status'],
            'scheduled_date' => $data['scheduled_date'] ?? null,
            'main_image' => $data['main_image'] ?? null,
            'id' => $id
        ]);
    
        // Aktualizacja tłumaczeń
        foreach ($data['translations'] as $langCode => $translation) {
            $stmt = $db->prepare("
                UPDATE news_translations 
                SET 
                    title = :title, 
                    summary = :summary, 
                    content = :content, 
                    meta_title = :meta_title, 
                    meta_description = :meta_description, 
                    meta_keywords = :meta_keywords, 
                    og_title = :og_title, 
                    og_description = :og_description, 
                    og_image = :og_image, 
                    updated_at = NOW()
                WHERE news_id = :news_id AND language_code = :language_code
            ");
            $stmt->execute([
                'title' => $translation['title'] ?? '',
                'summary' => $translation['summary'] ?? '',
                'content' => $translation['content'] ?? '',
                'meta_title' => $translation['meta_title'] ?? '',
                'meta_description' => $translation['meta_description'] ?? '',
                'meta_keywords' => $translation['meta_keywords'] ?? '',
                'og_title' => $translation['og_title'] ?? '',
                'og_description' => $translation['og_description'] ?? '',
                'og_image' => $translation['og_image'] ?? '',
                'news_id' => $id,
                'language_code' => $langCode
            ]);
        }
    }

    public static function delete($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM news WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function toggleStatus($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT status FROM news WHERE id = ?");
        $stmt->execute([$id]);
        $currentStatus = $stmt->fetchColumn();

        $newStatus = $currentStatus === 'published' ? 'frozen' : 'published';
        $stmt = $db->prepare("UPDATE news SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $id]);
    }

    public static function getGalleryAlbums() {
        $db = Database::getConnection();
        $query = "
            SELECT 
                ga.id, 
                gat.title 
            FROM gallery_albums ga
            LEFT JOIN gallery_album_translations gat ON ga.id = gat.album_id AND gat.language_code = :default_language
        ";
        $stmt = $db->prepare($query);
        $stmt->execute(['default_language' => Language::getDefaultLanguage()]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
}
