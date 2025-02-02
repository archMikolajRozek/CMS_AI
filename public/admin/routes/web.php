<?php

use Admin\Controllers\DashboardController;
use Admin\Controllers\NewsController;
use Admin\Controllers\UsersController;
use Admin\Controllers\GalleryController;
use Admin\Controllers\SettingsController;
use Admin\Controllers\ContactController;
use Admin\Controllers\VersionController;
use Admin\Controllers\LogsController;
use Admin\Controllers\PagesController;
use Admin\Controllers\ThemesController;
use Admin\Controllers\BackupsController;

return [
    // Dashboard
    '/admin' => [DashboardController::class, 'index'],
    '/admin\/' => [DashboardController::class, 'index'],

    '/admin/logout' => [DashboardController::class, 'logout'],

    // Aktualności
    '/admin/news' => [NewsController::class, 'index'],
    '/admin/news/create' => [NewsController::class, 'create'],
    '/admin/news/edit/{id}' => [NewsController::class, 'edit'],
    '/admin/news/delete/{id}' => [NewsController::class, 'delete'],
    '/admin/news/toggle-status/{id}' => [NewsController::class, 'toggleStatus'],

    // Użytkownicy
    '/admin/users' => [UsersController::class, 'index'],
    '/admin/users/create' => [UsersController::class, 'create'],
    '/admin/users/edit' => [UsersController::class, 'edit'],
    '/admin/users/delete' => [UsersController::class, 'delete'],

    // Galeria
    '/admin/gallery' => [GalleryController::class, 'index'],
    '/admin/gallery/create' => [GalleryController::class, 'create'],
    '/admin/gallery/edit' => [GalleryController::class, 'edit'],
    '/admin/gallery/delete' => [GalleryController::class, 'delete'],

    // Ustawienia: Moduły
    '/admin/settings' => [SettingsController::class, 'index'],
    '/admin/settings/modules' => [SettingsController::class, 'modules'],
    '/admin/settings/modules/{id}/{action}' => [SettingsController::class, 'toggleModule'],

    // Ustawienia: Jezyki
    '/admin/settings/languages' => [SettingsController::class, 'languages'],
    '/admin/settings/languages/add' => [SettingsController::class, 'addLanguage'],   
    '/admin/settings/languages/{id}/{action}' => [SettingsController::class, 'toggleLanguage'],
 

    // Moduł Kontakt
    '/admin/contact' => [ContactController::class, 'index'],
    '/admin/contact/save' => [ContactController::class, 'saveSettings'],
    '/admin/contact/save-form-settings' => [ContactController::class, 'saveFormSettings'], 

    '/admin/contact/messages' => [ContactController::class, 'messages'],
    '/admin/contact/messages/{id}/mark-read' => [ContactController::class, 'markAsRead'],

    // Wersja systemu
    '/admin/settings/version' => [VersionController::class, 'index'],
    '/admin/settings/version/update' => [VersionController::class, 'update'],

    // Logi
    '/admin/settings/logs' => [LogsController::class, 'index'],
    '/admin/settings/logs/toggle' => [LogsController::class, 'toggleLogging'],

    // Podstrony
    '/admin/pages' => [PagesController::class, 'index'],
    '/admin/pages/create' => [PagesController::class, 'create'],
    '/admin/pages/edit/{id}' => [PagesController::class, 'edit'],
    '/admin/pages/delete/{id}' => [PagesController::class, 'delete'],
    '/admin/pages/toggle-status/{id}' => [PagesController::class, 'toggleStatus'],
/*
    // Motywy
    '/admin/themes' => [ThemesController::class, 'index'],
    '/admin/themes/activate/{id}' => [ThemesController::class, 'activate'],
    '/admin/themes/delete/{id}' => [ThemesController::class, 'delete'],

    // Kopie zapasowe
    '/admin/settings/backups' => [BackupsController::class, 'index'],
    '/admin/settings/backups/create' => [BackupsController::class, 'create'],
    '/admin/settings/backups/delete/{id}' => [BackupsController::class, 'delete'],*/
];
