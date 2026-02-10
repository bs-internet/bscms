<?php

// Admin Protected Routes
$routes->group('admin', ['filter' => 'admin_auth', 'namespace' => 'App\Core\Modules\Content\Controllers'], function ($routes) {
    // Content Types
    $routes->get('content-types', 'ContentTypeController::index');
    $routes->get('content-types/create', 'ContentTypeController::create');
    $routes->post('content-types/store', 'ContentTypeController::store');
    $routes->get('content-types/edit/(:num)', 'ContentTypeController::edit/$1');
    $routes->post('content-types/update/(:num)', 'ContentTypeController::update/$1');
    $routes->get('content-types/delete/(:num)', 'ContentTypeController::delete/$1');

    // Content Type Fields (AJAX)
    $routes->post('content-type-fields/(:num)/store', 'ContentTypeFieldController::store/$1');
    $routes->post('content-type-fields/(:num)/update/(:num)', 'ContentTypeFieldController::update/$1/$2');
    $routes->delete('content-type-fields/(:num)/delete/(:num)', 'ContentTypeFieldController::delete/$1/$2');

    // Contents
    $routes->get('contents/(:num)', 'ContentController::index/$1');
    $routes->get('contents/(:num)/create', 'ContentController::create/$1');
    $routes->post('contents/(:num)/store', 'ContentController::store/$1');
    $routes->get('contents/(:num)/edit/(:num)', 'ContentController::edit/$1/$2');
    $routes->post('contents/(:num)/update/(:num)', 'ContentController::update/$1/$2');
    $routes->get('contents/(:num)/delete/(:num)', 'ContentController::delete/$1/$2');
    $routes->post('contents/(:num)/bulk-action', 'ContentController::bulkAction/$1');
    $routes->get('contents/(:num)/duplicate/(:num)', 'ContentController::duplicate/$1/$2');
    $routes->get('contents/relation-options/(:num)', 'ContentController::getRelationOptions/$1');
});

// Frontend Routes
$routes->group('', ['namespace' => 'App\Core\Modules\Content\Controllers'], function ($routes) {
    $routes->get('/', 'FrontendController::index');
    $routes->get('page/(:segment)', 'FrontendController::page/$1');
    // Category
    $routes->get('(:segment)/category/(:segment)', 'FrontendController::category/$1/$2');
    // Catch-all
    $routes->get('(:segment)/(:segment)', 'FrontendController::single/$1/$2');
    $routes->get('(:segment)', 'FrontendController::list/$1');
});
