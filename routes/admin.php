<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\ApparatusController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\GalleryAlbumController;
use App\Http\Controllers\Admin\GalleryPhotoController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TourismController;
use App\Http\Controllers\Admin\UmkmController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\VillageProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    // Village Profile
    Route::get('village-profile', [VillageProfileController::class, 'edit'])->name('village-profile.edit');
    Route::put('village-profile', [VillageProfileController::class, 'update'])->name('village-profile.update');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    // Users
    Route::resource('users', UserController::class)->except(['show']);

    // Content Management
    Route::resource('articles', ArticleController::class)->except(['show']);
    Route::resource('announcements', AnnouncementController::class)->except(['show']);
    Route::resource('agendas', AgendaController::class)->except(['show']);

    // Media & Gallery
    Route::resource('apparatus', ApparatusController::class)->except(['show']);
    Route::resource('gallery-albums', GalleryAlbumController::class);
    Route::post('gallery-albums/{album}/photos', [GalleryPhotoController::class, 'store'])->name('gallery-albums.photos.store');
    Route::delete('gallery-albums/{album}/photos/{photo}', [GalleryPhotoController::class, 'destroy'])->name('gallery-albums.photos.destroy');
    Route::resource('videos', VideoController::class)->except(['show']);

    // Data & Utilities
    Route::resource('complaints', ComplaintController::class)->except(['create', 'store', 'edit']);
    Route::resource('documents', DocumentController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('tourism', TourismController::class)->except(['show']);
    Route::resource('umkm', UmkmController::class)->except(['show']);
});
