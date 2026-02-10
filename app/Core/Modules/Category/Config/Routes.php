<?php

// Admin Protected Routes
$routes->group('admin', ['filter' => 'admin_auth', 'namespace' => 'App\Core\Modules\Category\Controllers'], function ($routes) {
    $routes->get('categories/(:num)', 'CategoryController::index/$1');
    $routes->get('categories/(:num)/create', 'CategoryController::create/$1');
    $routes->post('categories/(:num)/store', 'CategoryController::store/$1');
    $routes->get('categories/(:num)/edit/(:num)', 'CategoryController::edit/$1/$2');
    $routes->post('categories/(:num)/update/(:num)', 'CategoryController::update/$1/$2');
    $routes->get('categories/(:num)/delete/(:num)', 'CategoryController::delete/$1/$2');
    $routes->post('categories/(:num)/bulk-action', 'CategoryController::bulkAction/$1');
});
