<?php

// Admin Protected Routes
$routes->group('admin', ['filter' => 'admin_auth', 'namespace' => 'App\Core\Modules\Form\Controllers'], function ($routes) {
    $routes->get('forms', 'FormController::index');
    $routes->get('forms/create', 'FormController::create');
    $routes->post('forms/store', 'FormController::store');
    $routes->get('forms/edit/(:num)', 'FormController::edit/$1');
    $routes->post('forms/update/(:num)', 'FormController::update/$1');
    $routes->get('forms/delete/(:num)', 'FormController::delete/$1');
    $routes->get('forms/(:num)/submissions', 'FormController::submissions/$1');
    $routes->get('forms/(:num)/submissions/(:num)', 'FormController::submissionDetail/$1/$2');

    // Form Fields (AJAX)
    $routes->post('form-fields/(:num)/store', 'FormFieldController::store/$1');
    $routes->post('form-fields/(:num)/update/(:num)', 'FormFieldController::update/$1/$2');
    $routes->delete('form-fields/(:num)/delete/(:num)', 'FormFieldController::delete/$1/$2');
});

// Front-end / Global Routes (Submit)
$routes->post('form/submit/(:segment)', '\App\Core\Modules\Form\Controllers\FormController::submit/$1');
