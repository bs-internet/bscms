<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Core\Shared\Controllers');

/*
|--------------------------------------------------------------------------
| Module Route Autoloader
|--------------------------------------------------------------------------
|
| Instead of defining all routes in this single file, we load them
| from their respective module directories. This makes the application
| modular and easier to maintain.
|
*/
$modules = [
    'Auth',
    'System',
    'Category',
    'Form',
    'Component',
    'Media',
    'Menu',
    'Content', // Loaded last for Catch-All routes
];

foreach ($modules as $module) {
    $routeFile = APPPATH . 'Core/Modules/' . $module . '/Config/Routes.php';
    if (file_exists($routeFile)) {
        require $routeFile;
    }
}

/*
|--------------------------------------------------------------------------
| Note:
|--------------------------------------------------------------------------
| Each module's Routes.php file is responsible for defining its own
| protected or public routes using $routes->group() and filters.
|
*/
