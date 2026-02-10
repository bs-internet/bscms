<?php

// Admin Public Routes
$routes->group('admin', ['namespace' => 'App\Core\Modules\Auth\Controllers'], function ($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::authenticate');
    $routes->get('logout', 'AuthController::logout');
    $routes->post('logout-all', 'AuthController::logoutAllDevices');
    $routes->get('forgot-password', 'AuthController::forgotPassword');
    $routes->post('forgot-password', 'AuthController::sendResetLink');
    $routes->get('reset-password', 'AuthController::showResetForm');
    $routes->post('reset-password/update', 'AuthController::updatePassword');
});

// Admin Protected Routes (Roles)
$routes->group('admin', ['filter' => 'admin_auth', 'namespace' => 'App\Core\Modules\Auth\Controllers'], function ($routes) {
    $routes->get('roles', 'RoleController::index');
    $routes->get('roles/create', 'RoleController::create');
    $routes->post('roles/store', 'RoleController::store');
    $routes->get('roles/edit/(:num)', 'RoleController::edit/$1');
    $routes->post('roles/update/(:num)', 'RoleController::update/$1');
    $routes->get('roles/delete/(:num)', 'RoleController::delete/$1');
});
