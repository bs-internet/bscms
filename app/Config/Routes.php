<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Core\Shared\Controllers');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
$routes->group('admin', function ($routes) {

    // ========================================
    // Auth Module (No Filter)
    // ========================================
    $routes->group('', ['namespace' => 'App\Core\Modules\Auth\Controllers'], function ($routes) {
        $routes->get('login', 'AuthController::login');
        $routes->post('login', 'AuthController::authenticate');
        $routes->get('logout', 'AuthController::logout');
        $routes->post('logout-all', 'AuthController::logoutAllDevices');
        $routes->get('forgot-password', 'AuthController::forgotPassword');
        $routes->post('forgot-password', 'AuthController::sendResetLink');
        $routes->get('reset-password', 'AuthController::showResetForm');
        $routes->post('reset-password/update', 'AuthController::updatePassword');
    });

    // ========================================
    // Protected Admin Routes
    // ========================================
    $routes->group('', ['filter' => 'admin_auth'], function ($routes) {

        // System Module
        $routes->group('', ['namespace' => 'App\Core\Modules\System\Controllers'], function ($routes) {
            $routes->get('/', 'DashboardController::index');
            $routes->get('dashboard', 'DashboardController::index');

            $routes->get('settings', 'SettingController::index');
            $routes->post('settings/update', 'SettingController::update');

            $routes->get('profile', 'ProfileController::index');
            $routes->post('profile/update', 'ProfileController::update');

            $routes->get('sitemap', 'SitemapController::index');
            $routes->post('sitemap/generate', 'SitemapController::generate');
            $routes->get('sitemap/view', 'SitemapController::view');
            $routes->get('sitemap/delete', 'SitemapController::delete');
        });

        // Content Module
        $routes->group('', ['namespace' => 'App\Core\Modules\Content\Controllers'], function ($routes) {
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

        // Category Module
        $routes->group('', ['namespace' => 'App\Core\Modules\Category\Controllers'], function ($routes) {
            $routes->get('categories/(:num)', 'CategoryController::index/$1');
            $routes->get('categories/(:num)/create', 'CategoryController::create/$1');
            $routes->post('categories/(:num)/store', 'CategoryController::store/$1');
            $routes->get('categories/(:num)/edit/(:num)', 'CategoryController::edit/$1/$2');
            $routes->post('categories/(:num)/update/(:num)', 'CategoryController::update/$1/$2');
            $routes->get('categories/(:num)/delete/(:num)', 'CategoryController::delete/$1/$2');
            $routes->post('categories/(:num)/bulk-action', 'CategoryController::bulkAction/$1');
        });

        // Form Module
        $routes->group('', ['namespace' => 'App\Core\Modules\Form\Controllers'], function ($routes) {
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

        // Component Module
        $routes->group('', ['namespace' => 'App\Core\Modules\Component\Controllers'], function ($routes) {
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

        // Media Module
        $routes->group('', ['namespace' => 'App\Core\Modules\Media\Controllers'], function ($routes) {
            $routes->get('media', 'MediaController::index');
            $routes->post('media/upload', 'MediaController::upload');
            $routes->get('media/delete/(:num)', 'MediaController::delete/$1');
        });

        // Menu Module
        $routes->group('', ['namespace' => 'App\Core\Modules\Menu\Controllers'], function ($routes) {
            $routes->get('menus', 'MenuController::index');
            $routes->get('menus/create', 'MenuController::create');
            $routes->post('menus/store', 'MenuController::store');
            $routes->get('menus/edit/(:num)', 'MenuController::edit/$1');
            $routes->post('menus/update/(:num)', 'MenuController::update/$1');
            $routes->get('menus/delete/(:num)', 'MenuController::delete/$1');
        });
    });
});


/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
$routes->group('', ['namespace' => 'App\Core\Modules\Content\Controllers'], function ($routes) {
    $routes->get('/', 'FrontendController::index');
    $routes->get('page/(:segment)', 'FrontendController::page/$1');

    // Category
    $routes->get('(:segment)/category/(:segment)', 'FrontendController::category/$1/$2');

    // Catch-all
    $routes->get('(:segment)/(:segment)', 'FrontendController::single/$1/$2');
    $routes->get('(:segment)', 'FrontendController::list/$1');
});

// Sitemap Routes
$routes->get('sitemap.xml', '\App\Core\Modules\System\Controllers\SitemapController::index');

// Form Routes
$routes->post('form/submit/(:segment)', '\App\Core\Modules\Form\Controllers\FormController::submit/$1');

