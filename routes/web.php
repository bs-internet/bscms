<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\Dashboard\DashboardController;
use App\Http\Controllers\Backend\Pages\PagesController;
use App\Http\Controllers\Backend\Menu\MenuController;

use App\Http\Controllers\Backend\Modules\ModulesController;
use App\Http\Controllers\Backend\Modules\ContactController;
use App\Http\Controllers\Backend\Modules\GalleryController;
use App\Http\Controllers\Backend\Modules\NewsletterController;
use App\Http\Controllers\Backend\Modules\SliderController;

use App\Http\Controllers\Backend\Settings\SettingsController;
use App\Http\Controllers\Backend\Settings\CompanyController;
use App\Http\Controllers\Backend\Settings\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->prefix('admin')->group(function () {

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard');
    });

    Route::controller(PagesController::class)->prefix('pages')->group(function () {
        Route::get('/', 'index')->name('list');
        Route::get('/create', 'create')->name('create');
        Route::get('/edit/{page}', 'edit')->name('edit');
    });

    Route::controller(MenuController::class)->group(function () {
        Route::get('/menu', 'index')->name('menu');;
    });

    Route::prefix('modules')->group(function () {

        Route::controller(ModulesController::class)->group(function () {
            Route::get('/', 'index')->name('modules');;
        });

        Route::controller(ContactController::class)->group(function () {
            Route::get('/contact', 'index')->name('contact');;
        });

        Route::controller(GalleryController::class)->group(function () {
            Route::get('/gallery', 'index')->name('gallery');;
        });

        Route::controller(NewsletterController::class)->group(function () {
            Route::get('/newsletter', 'index')->name('newsletter');;
        });

        Route::controller(SliderController::class)->group(function () {
            Route::get('/slider', 'index')->name('slider');;
        });

    });

    Route::prefix('settings')->group(function () {

        Route::controller(SettingsController::class)->group(function () {
            Route::get('/', 'index')->name('settigns');;
        });

        Route::controller(CompanyController::class)->group(function () {
            Route::get('/company', 'index')->name('company');;
        });

        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'index')->name('profile');;
        });

    });

});
