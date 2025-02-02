<?php

namespace App\Middleware;

class AdminMiddleware {
    public function handle() {
        if ($_SESSION['user']['role'] !== 'Admin') {
            die('Access denied.');
        }
    }
}
