<?php

namespace Admin\Controllers;

use Admin\Models\Gallery;

class GalleryController {
    public function index() {
        $galleries = Gallery::getAll();
        require __DIR__ . '/../views/gallery/index.php';
    }

    public function create() {
        require __DIR__ . '/../views/gallery/create.php';
    }

    public function edit($id) {
        $gallery = Gallery::find($id);
        require __DIR__ . '/../views/gallery/edit.php';
    }

    public function delete($id) {
        Gallery::delete($id);
        header('Location: /admin/gallery');
        exit;
    }
}
