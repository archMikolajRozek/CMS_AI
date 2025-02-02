<?php

namespace Admin\Models;

use App\Database;

class Page {
    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT p.id, t.title, p.slug, p.status, p.created_at 
                            FROM pages p 
                            LEFT JOIN page_translations t ON p.id = t.page_id AND t.language_code = 'en'");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT 
                p.*, 
                t.language_code, 
                t.title, 
                t.content, 
                t.meta_title, 
                t.meta_description, 
                t.meta_keywords 
            FROM pages p 
            LEFT JOIN page_translations t 
            ON p.id = t.page_id 
            WHERE p.id = ? 
            LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public static function updateStatus($id, $status) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE pages SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
    }

    public static function create($data) {
        $db = Database::getConnection();

        // Wstaw dane do głównej tabeli `pages`
        $stmt = $db->prepare("INSERT INTO pages (slug, gallery_album_id, status) VALUES (?, ?, ?)");
        $stmt->execute([
            $data['slug'],
            $data['gallery_album_id'] ?? null,
            $data['status'] ?? 'draft'
        ]);

        $pageId = $db->lastInsertId();

        // Wstaw dane do tabeli `page_translations`
        $stmt = $db->prepare("INSERT INTO page_translations (page_id, language_code, title, content, meta_title, meta_description, meta_keywords) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $pageId,
            $data['language_code'] ?? 'en',
            $data['title'],
            $data['content'],
            $data['meta_title'] ?? null,
            $data['meta_description'] ?? null,
            $data['meta_keywords'] ?? null
        ]);
    }

    public static function update($id, $data) {
        $db = Database::getConnection();

        // Aktualizuj dane w tabeli `pages`
        $stmt = $db->prepare("UPDATE pages SET slug = ?, gallery_album_id = ?, status = ? WHERE id = ?");
        $stmt->execute([
            $data['slug'],
            $data['gallery_album_id'] ?? null,
            $data['status'] ?? 'draft',
            $id
        ]);

        // Aktualizuj dane w tabeli `page_translations`
        $stmt = $db->prepare("UPDATE page_translations 
                              SET title = ?, content = ?, meta_title = ?, meta_description = ?, meta_keywords = ? 
                              WHERE page_id = ? AND language_code = ?");
        $stmt->execute([
            $data['title'],
            $data['content'],
            $data['meta_title'] ?? null,
            $data['meta_description'] ?? null,
            $data['meta_keywords'] ?? null,
            $id,
            $data['language_code'] ?? 'en'
        ]);
    }
}