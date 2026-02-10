<?php

// Admin Protected Routes
$routes->group('admin', ['filter' => 'admin_auth', 'namespace' => 'App\Core\Modules\System\Controllers'], function ($routes) {
    $routes->get('/', 'DashboardController::index');
    $routes->get('dashboard', 'DashboardController::index');

    $routes->get('settings', 'SettingController::index');
    $routes->post('settings/update', 'SettingController::update');
    $routes->post('settings/clear-cache', 'SettingController::clearCache');

    // Plugin Management
    $routes->get('plugins', 'PluginController::index');
    $routes->post('plugins/toggle/(:segment)', 'PluginController::toggle/$1');

    $routes->get('profile', 'ProfileController::index');
    $routes->post('profile/update', 'ProfileController::update');

    $routes->get('sitemap', 'SitemapController::index');
    $routes->post('sitemap/generate', 'SitemapController::generate');
    $routes->get('sitemap/view', 'SitemapController::view');
    $routes->get('sitemap/delete', 'SitemapController::delete');
});

// Sitemap Global Route
$routes->get('sitemap.xml', '\App\Core\Modules\System\Controllers\SitemapController::index');
