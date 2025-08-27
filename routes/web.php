<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlacemarkController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\TrackingCertificateController;
use App\Http\Controllers\CertificateSearchController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// الصفحة الرئيسية
Route::get('/', function () {
    return view('layout'); 
});

// Auth Routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// صفحات محمية للمستخدمين المسجلين فقط
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('layout');
    })->name('dashboard');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('users', UserController::class)->names([
        'index' => 'user.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
});

/*
|--------------------------------------------------------------------------
| Routes for Placemark Certificate System
|--------------------------------------------------------------------------
*/
Route::get('/placemarks/upload', [PlacemarkController::class, 'upload'])->name('placemarks.upload'); // جميع المستخدمين
Route::post('/placemarks/import', [PlacemarkController::class, 'import'])->name('placemarks.import'); // جميع المستخدمين
Route::post('/placemarks/certificate/preview', [PlacemarkController::class, 'previewCertificate'])->name('placemarks.certificate.preview'); // جميع المستخدمين
Route::post('/placemarks/certificate_image', [PlacemarkController::class, 'generateImageFromHTML'])->name('certificate.generate.image'); // جميع المستخدمين
Route::get('/certificate/show/{filename}', [PlacemarkController::class, 'showCertificateImage'])->name('placemarks.showCertificateImage'); // جميع المستخدمين
Route::post('/placemarks/save-data', [PlacemarkController::class, 'saveData'])->name('placemarks.saveData'); // جميع المستخدمين

/*
|--------------------------------------------------------------------------
| Routes for Manual Certificates
|--------------------------------------------------------------------------
*/
Route::get('/manual/manual_form', [ManualController::class, 'manualForm'])->name('placemarks.manual'); // جميع المستخدمين
Route::post('/placemarks/manual/generate', [PlacemarkController::class, 'generateManualCertificate'])->name('placemarks.manual.generate'); // جميع المستخدمين
Route::get('/manual/survey', [ManualController::class, 'surveyForm'])->name('manual.survey'); // جميع المستخدمين
Route::get('/manual/utilities', [ManualController::class, 'utilitiesForm'])->name('manual.utilities'); // جميع المستخدمين
Route::get('/manual/tracking', [ManualController::class, 'trackingForm'])->name('manual.tracking'); // جميع المستخدمين

/*
|--------------------------------------------------------------------------
| Routes for Tracking Certificates
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // 🟡 Customer Service: إضافة معاملات
    Route::middleware('role:customer_service')->group(function () {
        Route::get('/tracking-certificates/create', [TrackingCertificateController::class, 'create'])->name('tracking_certificates.create'); // Customer Service
        Route::post('/tracking-certificates/save-image', [TrackingCertificateController::class, 'saveImage'])->name('tracking_certificates.save_image'); // Customer Service
        Route::post('/tracking-certificates/store-from-existing', [TrackingCertificateController::class, 'storeFromExisting'])->name('tracking_certificates.storeFromExisting'); // Customer Service
    });

    // 🔵 Reviewer: المراجعة فقط
    Route::middleware('role:reviewer')->group(function () {
        Route::get('/tracking-certificates/review/{status}', [TrackingCertificateController::class, 'reviewByStatus'])->name('tracking_certificates.review'); // Reviewer
        Route::get('/tracking/review', [TrackingCertificateController::class, 'reviewByStatus'])->name('tracking.review'); // Reviewer
        Route::post('/tracking-certificates/store', [TrackingCertificateController::class, 'store'])->name('tracking_certificates.store'); // Reviewer
        Route::post('/tracking-certificates/save-temp-image', [TrackingCertificateController::class, 'saveTemporaryImage'])->name('tracking_certificates.save_temp_image'); // Reviewer
        Route::get('/tracking-certificates/create_from_existing/{id}', [TrackingCertificateController::class, 'createFromExisting'])->name('tracking_certificates.create_from_existing'); // Reviewer
    });

    // 🟣 Data Entry + Reviewer: الإدخال والمراجعة
    Route::middleware('role:data_entry,reviewer')->group(function () {
        Route::get('/tracking-certificates/delivery/{status}', [TrackingCertificateController::class, 'deliveryByStatus'])->name('tracking_certificates.delivery'); // Data Entry, Reviewer
        Route::get('/tracking-certificates/stifaa', [TrackingCertificateController::class, 'stifaa'])->name('tracking_certificates.stifaa'); // Data Entry, Reviewer
        Route::get('/tracking/delivery', [TrackingCertificateController::class, 'deliveryByStatus'])->name('tracking.delivery'); // Data Entry, Reviewer
        Route::get('/tracking/stifaa', [TrackingCertificateController::class, 'stifaa'])->name('tracking.stifaa'); // Data Entry, Reviewer
        Route::get('/manual', [ManualController::class, 'chooseType'])->name('manual.choose'); // Data Entry, Reviewer
    });

    // 🌍 Routes مشتركة لكل الأدوار (Admin يشوفهم تلقائيًا)
    Route::get('/tracking-certificates/{id}/edit', [TrackingCertificateController::class, 'edit'])->name('tracking_certificates.edit'); // كل الأدوار
    Route::put('/tracking-certificates/{id}/update', [TrackingCertificateController::class, 'update'])->name('tracking_certificates.update'); // كل الأدوار
    Route::post('/tracking-certificates/preview', [TrackingCertificateController::class, 'previewForm'])->name('manual.preview'); // كل الأدوار
    Route::get('/tracking-certificates/{id}/images', [TrackingCertificateController::class, 'showCertificateImages'])->name('certificates.showImages'); // كل الأدوار
    Route::post('/search', [TrackingCertificateController::class, 'searchByTransactionNumber'])->name('certificates.search'); // كل الأدوار
    Route::get('/search', [TrackingCertificateController::class, 'showSearchForm'])->name('certificates.search.form'); // كل الأدوار

    // 🟢 Admin فقط: راوتات خاصة بالادمن
    Route::middleware('role:admin')->group(function () {
        Route::get('/tracking-certificates/all', [TrackingCertificateController::class, 'all'])->name('tracking_certificates.all'); // Admin
        Route::delete('/tracking-certificates/{id}/images/delete', [TrackingCertificateController::class, 'deleteCertificateImage'])->name('certificates.deleteImage'); // Admin
        Route::post('/tracking-certificates/{id}/update-status', [TrackingCertificateController::class, 'updateStatus'])->name('tracking_certificates.update_status'); // Admin
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index'); // Admin
        Route::post('/transactions/{id}/deliver', [TransactionController::class, 'deliver'])->name('transactions.deliver'); // Admin
        Route::match(['get', 'post'], '/transactions/search', [TransactionController::class, 'search'])->name('transactions.search'); // Admin
    });
});

/*
|--------------------------------------------------------------------------
| Routes for Certificate Search & Edit
|--------------------------------------------------------------------------
*/
Route::get('/certificates/{type}/{id}/edit', [CertificateSearchController::class, 'edit'])->name('certificates.edit'); // كل الأدوار
Route::post('/certificates/{type}/{id}/update', [CertificateSearchController::class, 'update'])->name('certificates.update'); // كل الأدوار

