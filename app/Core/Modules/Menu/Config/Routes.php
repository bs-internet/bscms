<?php

// Admin Protected Routes
$routes->group('admin', ['filter' => 'admin_auth', 'namespace' => 'App\Core\Modules\Menu\Controllers'], function ($routes) {
    $routes->get('menus', 'MenuController::index');
    $routes->get('menus/create', 'MenuController::create');
    $routes->post('menus/store', 'MenuController::store');
    $routes->get('menus/edit/(:num)', 'MenuController::edit/$1');
    $routes->post('menus/update/(:num)', 'MenuController::update/$1');
    $routes->get('menus/delete/(:num)', 'MenuController::delete/$1');
});
