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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->prefix('admin')->as('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('pages', PagesController::class)->except(['show']);

    Route::get('menu', [MenuController::class, 'index'])->name('menu.index');
    Route::post('menu', [MenuController::class, 'store'])->name('menu.store');
    Route::put('menu/{menu}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('menu/{menu}', [MenuController::class, 'destroy'])->name('menu.destroy');

    Route::get('modules', [ModulesController::class, 'index'])->name('modules.index');
    Route::get('modules/contact', [ContactController::class, 'index'])->name('modules.contact');
    Route::put('modules/contact', [ContactController::class, 'update'])->name('modules.contact.update');
    Route::get('modules/gallery', [GalleryController::class, 'index'])->name('modules.gallery');
    Route::put('modules/gallery', [GalleryController::class, 'update'])->name('modules.gallery.update');
    Route::get('modules/newsletter', [NewsletterController::class, 'index'])->name('modules.newsletter');
    Route::put('modules/newsletter', [NewsletterController::class, 'update'])->name('modules.newsletter.update');
    Route::get('modules/slider', [SliderController::class, 'index'])->name('modules.slider');
    Route::put('modules/slider', [SliderController::class, 'update'])->name('modules.slider.update');

    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::get('settings/company', [CompanyController::class, 'index'])->name('settings.company.index');
    Route::put('settings/company', [CompanyController::class, 'update'])->name('settings.company.update');

    Route::get('settings/profile', [ProfileController::class, 'index'])->name('settings.profile.index');
    Route::put('settings/profile', [ProfileController::class, 'update'])->name('settings.profile.update');
});
