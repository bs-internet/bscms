<?php

// Admin Protected Routes
$routes->group('admin', ['filter' => 'admin_auth', 'namespace' => 'App\Core\Modules\Component\Controllers'], function ($routes) {
    $routes->get('components', 'ComponentController::index');
    $routes->get('components/create', 'ComponentController::create');
    $routes->post('components/store', 'ComponentController::store');
    $routes->get('components/edit/(:num)', 'ComponentController::edit/$1');
    $routes->post('components/update/(:num)', 'ComponentController::update/$1');
    $routes->get('components/delete/(:num)', 'ComponentController::delete/$1');
    $routes->post('components/(:num)/save-global-data', 'ComponentController::saveGlobalData/$1');

    // Component Fields (AJAX)
    $routes->post('component-fields/(:num)/store', 'ComponentFieldController::store/$1');
    $routes->post('component-fields/(:num)/update/(:num)', 'ComponentFieldController::update/$1/$2');
    $routes->delete('component-fields/(:num)/delete/(:num)', 'ComponentFieldController::delete/$1/$2');

    // Component Instances (AJAX)
    $routes->post('component-instances/(:num)/(:num)/store', 'ComponentInstanceController::store/$1/$2');
    $routes->post('component-instances/(:num)/update-data', 'ComponentInstanceController::updateData/$1');
    $routes->delete('component-instances/(:num)/(:num)/delete', 'ComponentInstanceController::delete/$1/$2');
    $routes->post('component-instances/(:num)/reorder', 'ComponentInstanceController::reorder/$1');
});
