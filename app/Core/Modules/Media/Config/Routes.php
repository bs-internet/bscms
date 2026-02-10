<?php

// Admin Protected Routes
$routes->group('admin', ['filter' => 'admin_auth', 'namespace' => 'App\Core\Modules\Media\Controllers'], function ($routes) {
    $routes->get('media', 'MediaController::index');
    $routes->get('media/browse', 'MediaController::browse');
    $routes->post('media/upload', 'MediaController::upload');
    $routes->post('media/bulk-delete', 'MediaController::bulkDelete');
    $routes->get('media/delete/(:num)', 'MediaController::delete/$1');
});
