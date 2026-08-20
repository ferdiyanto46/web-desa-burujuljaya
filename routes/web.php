<?php

use App\Http\Controllers\Frontend\AgendaController;
use App\Http\Controllers\Frontend\AnnouncementController;
use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\ComplaintController;
use App\Http\Controllers\Frontend\ContactInformationController;
use App\Http\Controllers\Frontend\DocumentController;
use App\Http\Controllers\Frontend\FaqController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\Frontend\TourismController;
use App\Http\Controllers\Frontend\UmkmController;
use App\Http\Controllers\Frontend\VideoController;
use App\Http\Controllers\Frontend\VillagePageController;
use App\Http\Controllers\Frontend\VillageServiceController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Village Pages (CMS)
Route::get('/profil', [VillagePageController::class, 'index'])->name('profile.index');
Route::get('/profil/{slug}', [VillagePageController::class, 'show'])->name('profile.show');

// Village Services
Route::get('/layanan', [VillageServiceController::class, 'index'])->name('village-services.index');
Route::get('/layanan/{slug}', [VillageServiceController::class, 'show'])->name('village-services.show');

// Contact Information
Route::get('/kontak', [ContactInformationController::class, 'index'])->name('contact-information.index');
Route::get('/kontak/{slug}', [ContactInformationController::class, 'show'])->name('contact-information.show');

// FAQs
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/faq/{slug}', [FaqController::class, 'show'])->name('faq.show');

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
Route::get('/umkm/{umkm:slug}', [UmkmController::class, 'show'])->name('umkm.show');

Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/dokumen', [DocumentController::class, 'index'])->name('documents.index');
Route::get('/dokumen/{ulid}/download', [DocumentController::class, 'download'])->name('documents.download');

// Citizen Services
Route::get('/pengaduan', [ComplaintController::class, 'create'])->name('complaints.create');
Route::post('/pengaduan', [ComplaintController::class, 'store'])->name('complaints.store');
Route::get('/lacak-pengaduan', [ComplaintController::class, 'track'])->name('complaints.track');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('dashboard', '/admin/dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
