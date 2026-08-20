<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\ApparatusController;
use App\Http\Controllers\Admin\ApparatusPositionController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\ContactInformationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\GalleryPhotoController;
use App\Http\Controllers\Admin\HomepageSectionController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TourismCategoryController;
use App\Http\Controllers\Admin\TourismController;
use App\Http\Controllers\Admin\TourismFacilityController;
use App\Http\Controllers\Admin\TourismPhotoController;
use App\Http\Controllers\Admin\UmkmCategoryController;
use App\Http\Controllers\Admin\UmkmController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\VillagePageController;
use App\Http\Controllers\Admin\VillageProfileController;
use App\Http\Controllers\Admin\VillageServiceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    // Village Profile (Singleton)
    Route::get('village-profile', [VillageProfileController::class, 'edit'])->name('village-profile.edit');
    Route::match(['put', 'post'], 'village-profile', [VillageProfileController::class, 'update'])->name('village-profile.update');

    // Village Pages (CMS)
    Route::post('village-pages/{village_page}/restore', [VillagePageController::class, 'restore'])->name('village-pages.restore')->withTrashed();
    Route::delete('village-pages/{village_page}/force-delete', [VillagePageController::class, 'forceDelete'])->name('village-pages.force-delete')->withTrashed();
    Route::resource('village-pages', VillagePageController::class)->parameters(['village-pages' => 'village_page']);

    // Village Services
    Route::post('village-services/{village_service}/restore', [VillageServiceController::class, 'restore'])->name('village-services.restore')->withTrashed();
    Route::delete('village-services/{village_service}/force-delete', [VillageServiceController::class, 'forceDelete'])->name('village-services.force-delete')->withTrashed();
    Route::resource('village-services', VillageServiceController::class)->parameters(['village-services' => 'village_service']);

    // Contact Information
    Route::post('contact-information/{contact_information}/restore', [ContactInformationController::class, 'restore'])->name('contact-information.restore')->withTrashed();
    Route::delete('contact-information/{contact_information}/force-delete', [ContactInformationController::class, 'forceDelete'])->name('contact-information.force-delete')->withTrashed();
    Route::resource('contact-information', ContactInformationController::class)->parameters(['contact-information' => 'contact_information']);

    // FAQs
    Route::post('faqs/{faq}/restore', [FaqController::class, 'restore'])->name('faqs.restore')->withTrashed();
    Route::delete('faqs/{faq}/force-delete', [FaqController::class, 'forceDelete'])->name('faqs.force-delete')->withTrashed();
    Route::resource('faqs', FaqController::class);

    // Homepage Sections
    Route::post('homepage-sections/reorder', [HomepageSectionController::class, 'reorder'])->name('homepage-sections.reorder');
    Route::patch('homepage-sections/{homepage_section}/toggle', [HomepageSectionController::class, 'toggle'])->name('homepage-sections.toggle');
    Route::resource('homepage-sections', HomepageSectionController::class)->only(['index', 'edit', 'update'])->parameters(['homepage-sections' => 'homepage_section']);

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    // Users & Logs & System
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('audit', [AuditController::class, 'index'])->name('audit.index');
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('backups', [BackupController::class, 'index'])->name('backups.index');
    Route::post('backups', [BackupController::class, 'store'])->name('backups.store');
    Route::get('backups/{file}', [BackupController::class, 'download'])->name('backups.download');
    Route::delete('backups/{file}', [BackupController::class, 'destroy'])->name('backups.destroy');
    Route::get('cache', [CacheController::class, 'index'])->name('cache.index');
    Route::post('cache/clear', [CacheController::class, 'clear'])->name('cache.clear');

    // Content Management
    Route::resource('articles', ArticleController::class)->except(['show']);
    Route::resource('announcements', AnnouncementController::class)->except(['show']);
    Route::resource('agendas', AgendaController::class)->except(['show']);

    Route::post('apparatus/{apparatus}', [ApparatusController::class, 'update'])->name('apparatus.update.post');
    Route::resource('apparatus', ApparatusController::class)->parameters(['apparatus' => 'apparatus'])->except(['show']);
    Route::resource('apparatus-positions', ApparatusPositionController::class)->parameters(['apparatus-positions' => 'apparatus_position'])->except(['show', 'create', 'edit']);
    Route::resource('galleries', GalleryController::class)->except(['show']);
    Route::post('galleries/{gallery}/photos', [GalleryPhotoController::class, 'store'])->name('galleries.photos.store');
    Route::put('galleries/{gallery}/photos/reorder', [GalleryPhotoController::class, 'reorder'])->name('galleries.photos.reorder');
    Route::put('galleries/{gallery}/photos/{photo}', [GalleryPhotoController::class, 'update'])->name('galleries.photos.update');
    Route::delete('galleries/{gallery}/photos/{photo}', [GalleryPhotoController::class, 'destroy'])->name('galleries.photos.destroy');
    Route::resource('videos', VideoController::class)->except(['show']);

    // Data & Utilities
    Route::resource('complaints', ComplaintController::class)->except(['create', 'store', 'edit']);

    Route::post('documents/{document}/restore', [DocumentController::class, 'restore'])->name('documents.restore')->withTrashed();
    Route::delete('documents/{document}/force-delete', [DocumentController::class, 'forceDelete'])->name('documents.force-delete')->withTrashed();
    Route::resource('documents', DocumentController::class)->except(['show']);

    Route::resource('product-categories', ProductCategoryController::class)->except(['show']);
    Route::post('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore')->withTrashed();
    Route::delete('products/{product}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete')->withTrashed();
    Route::resource('products', ProductController::class);

    Route::resource('tourism-categories', TourismCategoryController::class)->except(['show']);
    Route::resource('tourism-facilities', TourismFacilityController::class)->except(['show']);
    Route::post('tourisms/{tourism}/restore', [TourismController::class, 'restore'])->name('tourisms.restore')->withTrashed();
    Route::delete('tourisms/{tourism}/force-delete', [TourismController::class, 'forceDelete'])->name('tourisms.force-delete')->withTrashed();
    Route::delete('tourisms/{tourism}/photos/{photo}', [TourismPhotoController::class, 'destroy'])->name('tourisms.photos.destroy');
    Route::resource('tourisms', TourismController::class)->except(['show']);

    Route::resource('umkm-categories', UmkmCategoryController::class);
    Route::post('umkm/{umkm}/restore', [UmkmController::class, 'restore'])->name('umkm.restore')->withTrashed();
    Route::delete('umkm/{umkm}/force-delete', [UmkmController::class, 'forceDelete'])->name('umkm.force-delete')->withTrashed();
    Route::resource('umkm', UmkmController::class);
});
