<?php

use App\Http\Controllers\Frontend\AgendaController;
use App\Http\Controllers\Frontend\AnnouncementController;
use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\ComplaintController;
use App\Http\Controllers\Frontend\DocumentController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\TourismController;
use App\Http\Controllers\Frontend\UmkmController;
use App\Http\Controllers\Frontend\VideoController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Pages
Route::get('/profil', [PageController::class, 'profile'])->name('pages.profile');
Route::get('/visi-misi', [PageController::class, 'visionMission'])->name('pages.vision-mission');
Route::get('/aparatur', [PageController::class, 'apparatus'])->name('pages.apparatus');

// Content Management
Route::get('/artikel', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/pengumuman', [AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('/pengumuman/{slug}', [AnnouncementController::class, 'show'])->name('announcements.show');

Route::get('/agenda', [AgendaController::class, 'index'])->name('agendas.index');
Route::get('/agenda/{slug}', [AgendaController::class, 'show'])->name('agendas.show');

// Media & Utilities
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/galeri/{slug}', [GalleryController::class, 'show'])->name('gallery.show');
Route::get('/video', [VideoController::class, 'index'])->name('videos.index');

Route::get('/wisata', [TourismController::class, 'index'])->name('tourism.index');
Route::get('/wisata/{slug}', [TourismController::class, 'show'])->name('tourism.show');

Route::get('/umkm', [UmkmController::class, 'index'])->name('umkm.index');
Route::get('/umkm/{slug}', [UmkmController::class, 'show'])->name('umkm.show');

Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/dokumen', [DocumentController::class, 'index'])->name('documents.index');
Route::get('/dokumen/{ulid}/download', [DocumentController::class, 'download'])->name('documents.download');

// Citizen Services
Route::get('/pengaduan', [ComplaintController::class, 'create'])->name('complaints.create');
Route::post('/pengaduan', [ComplaintController::class, 'store'])->name('complaints.store');
Route::get('/lacak-pengaduan', [ComplaintController::class, 'track'])->name('complaints.track');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
