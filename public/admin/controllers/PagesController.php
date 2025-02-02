<?php

namespace Admin\Controllers;

use Admin\Models\Page;

class PagesController {
    public function index() {
        // Pobranie listy podstron
        $pages = Page::getAll();

        // Załaduj widok z listą podstron
        require __DIR__ . '/../views/pages/index.php';
    }

    public function toggleStatus($id) {
        $page = Page::find($id);

        if (!$page) {
            die('Podstrona nie istnieje.');
        }

        $newStatus = ($page['status'] === 'draft') ? 'published' : 'draft';
        Page::updateStatus($id, $newStatus);

        header('Location: /admin/pages');
        exit;
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validatePageData($_POST);

            if (!empty($errors)) {
                require __DIR__ . '/../views/pages/create.php';
                return;
            }

            Page::create($_POST);
            header('Location: /admin/pages');
            exit;
        }

        require __DIR__ . '/../views/pages/create.php';
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validatePageData($_POST);

            if (!empty($errors)) {
                $page = Page::find($id);
                require __DIR__ . '/../views/pages/edit.php';
                return;
            }

            Page::update($id, $_POST);
            header('Location: /admin/pages');
            exit;
        }

        $page = Page::find($id);
        require __DIR__ . '/../views/pages/edit.php';
    }

    private function validatePageData($data) {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'Tytuł jest wymagany.';
        }

        if (empty($data['slug'])) {
            $errors['slug'] = 'Slug (URL) jest wymagany.';
        } elseif (!preg_match('/^[a-z0-9-]+$/', $data['slug'])) {
            $errors['slug'] = 'Slug może zawierać tylko małe litery, cyfry i myślniki.';
        }

        if (empty($data['content'])) {
            $errors['content'] = 'Zawartość jest wymagana.';
        }

        return $errors;
    }
}