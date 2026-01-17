<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Frontend Routes
$routes->get('/', 'FrontendController::index');
$routes->get('page/(:segment)', 'FrontendController::page/$1');
$routes->get('(:segment)/(:segment)', 'FrontendController::single/$1/$2');
$routes->get('(:segment)', 'FrontendController::list/$1');
$routes->get('(:segment)/category/(:segment)', 'FrontendController::category/$1/$2');
// Sitemap Routes
$routes->get('sitemap.xml', 'SitemapController::index');
// Form Routes
$routes->post('form/submit/(:segment)', 'FormController::submit/$1');

// Admin Routes
$routes->group('admin', function($routes) {
    // Auth
    $routes->get('login', 'Admin\AuthController::login');
    $routes->post('login', 'Admin\AuthController::authenticate');
    $routes->get('logout', 'Admin\AuthController::logout');

    // Dashboard
    $routes->get('dashboard', 'Admin\DashboardController::index');

    // Content Types
    $routes->get('content-types', 'Admin\ContentTypeController::index');
    $routes->get('content-types/create', 'Admin\ContentTypeController::create');
    $routes->post('content-types/store', 'Admin\ContentTypeController::store');
    $routes->get('content-types/edit/(:num)', 'Admin\ContentTypeController::edit/$1');
    $routes->post('content-types/update/(:num)', 'Admin\ContentTypeController::update/$1');
    $routes->get('content-types/delete/(:num)', 'Admin\ContentTypeController::delete/$1');

    // Content Type Fields (AJAX)
    $routes->post('content-type-fields/(:num)/store', 'Admin\ContentTypeFieldController::store/$1');
    $routes->post('content-type-fields/(:num)/update/(:num)', 'Admin\ContentTypeFieldController::update/$1/$2');
    $routes->delete('content-type-fields/(:num)/delete/(:num)', 'Admin\ContentTypeFieldController::delete/$1/$2');

    // Contents
    $routes->get('contents/(:num)', 'Admin\ContentController::index/$1');
    $routes->get('contents/(:num)/create', 'Admin\ContentController::create/$1');
    $routes->post('contents/(:num)/store', 'Admin\ContentController::store/$1');
    $routes->get('contents/(:num)/edit/(:num)', 'Admin\ContentController::edit/$1/$2');
    $routes->post('contents/(:num)/update/(:num)', 'Admin\ContentController::update/$1/$2');
    $routes->get('contents/(:num)/delete/(:num)', 'Admin\ContentController::delete/$1/$2');
    $routes->post('contents/(:num)/bulk-action', 'Admin\ContentController::bulkAction/$1');
    $routes->get('contents/(:num)/duplicate/(:num)', 'Admin\ContentController::duplicate/$1/$2');

    // Categories
    $routes->get('categories/(:num)', 'Admin\CategoryController::index/$1');
    $routes->get('categories/(:num)/create', 'Admin\CategoryController::create/$1');
    $routes->post('categories/(:num)/store', 'Admin\CategoryController::store/$1');
    $routes->get('categories/(:num)/edit/(:num)', 'Admin\CategoryController::edit/$1/$2');
    $routes->post('categories/(:num)/update/(:num)', 'Admin\CategoryController::update/$1/$2');
    $routes->get('categories/(:num)/delete/(:num)', 'Admin\CategoryController::delete/$1/$2');
    $routes->post('categories/(:num)/bulk-action', 'Admin\CategoryController::bulkAction/$1');

    // Forms
    $routes->get('forms', 'Admin\FormController::index');
    $routes->get('forms/create', 'Admin\FormController::create');
    $routes->post('forms/store', 'Admin\FormController::store');
    $routes->get('forms/edit/(:num)', 'Admin\FormController::edit/$1');
    $routes->post('forms/update/(:num)', 'Admin\FormController::update/$1');
    $routes->get('forms/delete/(:num)', 'Admin\FormController::delete/$1');
    $routes->get('forms/(:num)/submissions', 'Admin\FormController::submissions/$1');
    $routes->get('forms/(:num)/submissions/(:num)', 'Admin\FormController::submissionDetail/$1/$2');

    // Form Fields (AJAX)
    $routes->post('form-fields/(:num)/store', 'Admin\FormFieldController::store/$1');
    $routes->post('form-fields/(:num)/update/(:num)', 'Admin\FormFieldController::update/$1/$2');
    $routes->delete('form-fields/(:num)/delete/(:num)', 'Admin\FormFieldController::delete/$1/$2');

    // Components
    $routes->get('components', 'Admin\ComponentController::index');
    $routes->get('components/create', 'Admin\ComponentController::create');
    $routes->post('components/store', 'Admin\ComponentController::store');
    $routes->get('components/edit/(:num)', 'Admin\ComponentController::edit/$1');
    $routes->post('components/update/(:num)', 'Admin\ComponentController::update/$1');
    $routes->get('components/delete/(:num)', 'Admin\ComponentController::delete/$1');
    $routes->post('components/(:num)/save-global-data', 'Admin\ComponentController::saveGlobalData/$1');

    // Component Fields (AJAX)
    $routes->post('component-fields/(:num)/store', 'Admin\ComponentFieldController::store/$1');
    $routes->post('component-fields/(:num)/update/(:num)', 'Admin\ComponentFieldController::update/$1/$2');
    $routes->delete('component-fields/(:num)/delete/(:num)', 'Admin\ComponentFieldController::delete/$1/$2');

    // Component Instances (AJAX)
    $routes->post('component-instances/(:num)/(:num)/store', 'Admin\ComponentInstanceController::store/$1/$2');
    $routes->post('component-instances/(:num)/update-data', 'Admin\ComponentInstanceController::updateData/$1');
    $routes->delete('component-instances/(:num)/(:num)/delete', 'Admin\ComponentInstanceController::delete/$1/$2');
    $routes->post('component-instances/(:num)/reorder', 'Admin\ComponentInstanceController::reorder/$1');

    // Media
    $routes->get('media', 'Admin\MediaController::index');
    $routes->post('media/upload', 'Admin\MediaController::upload');
    $routes->get('media/delete/(:num)', 'Admin\MediaController::delete/$1');

    // Menus
    $routes->get('menus', 'Admin\MenuController::index');
    $routes->get('menus/create', 'Admin\MenuController::create');
    $routes->post('menus/store', 'Admin\MenuController::store');
    $routes->get('menus/edit/(:num)', 'Admin\MenuController::edit/$1');
    $routes->post('menus/update/(:num)', 'Admin\MenuController::update/$1');
    $routes->get('menus/delete/(:num)', 'Admin\MenuController::delete/$1');

    // Settings
    $routes->get('settings', 'Admin\SettingController::index');
    $routes->post('settings/update', 'Admin\SettingController::update');

    // Sitemap
    $routes->get('sitemap', 'Admin\SitemapController::index');
    $routes->post('sitemap/generate', 'Admin\SitemapController::generate');
    $routes->get('sitemap/view', 'Admin\SitemapController::view');
    $routes->get('sitemap/delete', 'Admin\SitemapController::delete');    
});