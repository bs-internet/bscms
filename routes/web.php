<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\CompanyController;
use App\Http\Controllers\Backend\GalleryController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Backend\NewsletterController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\SettingController;

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

Route::prefix('admin')->group(function () {

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard');
    });

    Route::controller(PageController::class)->prefix('page')->group(function () {
        Route::get('/', 'index')->name('pages');;
        /*Route::get('/new', 'edit');
        Route::get('/edit/{page}', 'detail');

        Route::post('/new', 'store');*/
    });

    Route::controller(MenuController::class)->group(function () {
        Route::get('/', 'index')->name('menu');;
    });

    Route::controller(CompanyController::class)->group(function () {
        Route::get('/', 'index')->name('company');;
    });

    Route::controller(ContactController::class)->group(function () {
        Route::get('/', 'index')->name('contact');;
    });

    Route::controller(GalleryController::class)->group(function () {
        Route::get('/', 'index')->name('gallery');;
    });

    Route::controller(SliderController::class)->group(function () {
        Route::get('/', 'index')->name('slider');;
    });

    Route::controller(ContactController::class)->group(function () {
        Route::get('/', 'index')->name('contact');;
    });

    Route::controller(NewsletterController::class)->group(function () {
        Route::get('/', 'index')->name('newsletter');;
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/', 'index')->name('profile');;
    });

    Route::controller(SettingController::class)->group(function () {
        Route::get('/', 'index')->name('settigns');;
    });

});
