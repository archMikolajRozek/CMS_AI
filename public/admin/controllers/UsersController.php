<?php

namespace Admin\Controllers;

use Admin\Models\User;

class UsersController {
    public function index() {
        $users = User::getAll();
        require __DIR__ . '/../views/users/index.php';
    }

    public function create() {
        require __DIR__ . '/../views/users/create.php';
    }

    public function edit($id) {
        $user = User::find($id);
        require __DIR__ . '/../views/users/edit.php';
    }

    public function delete($id) {
        User::delete($id);
        header('Location: /admin/users');
        exit;
    }
}
