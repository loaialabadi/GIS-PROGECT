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



// routes/web.php


Route::get('/', function () {
    return view('layout'); 
});

// ✅ Auth Routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// ✅ صفحات محمية للمستخدم المسجل دخول فقط
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('layout'); // صفحة للوحة التحكم بعد تسجيل الدخول
    })->name('dashboard');
});

// ✅ Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('users', UserController::class)->names([
        'index' => 'admin.users.index',
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
Route::get('/placemarks/upload', [PlacemarkController::class, 'upload'])->name('placemarks.upload');
Route::post('/placemarks/import', [PlacemarkController::class, 'import'])->name('placemarks.import');
Route::post('/placemarks/certificate/preview', [PlacemarkController::class, 'previewCertificate'])->name('placemarks.certificate.preview');
Route::post('/placemarks/certificate_image', [PlacemarkController::class, 'generateImageFromHTML'])->name('certificate.generate.image');
Route::get('/certificate/show/{filename}', [PlacemarkController::class, 'showCertificateImage'])->name('placemarks.showCertificateImage');
Route::post('/placemarks/save-data', [PlacemarkController::class, 'saveData'])->name('placemarks.saveData');
// Route::get('/layout', [PlacemarkController::class, 'layout'])->name('layoutd');


/*
|--------------------------------------------------------------------------
| Routes for Manual Certificates
|--------------------------------------------------------------------------
*/
Route::get('/manual/manual_form', [ManualController::class, 'manualForm'])->name('placemarks.manual');
Route::post('/placemarks/manual/generate', [PlacemarkController::class, 'generateManualCertificate'])->name('placemarks.manual.generate');
Route::get('/manual', [ManualController::class, 'chooseType'])->name('manual.choose');
Route::get('/manual/survey', [ManualController::class, 'surveyForm'])->name('manual.survey');
Route::get('/manual/utilities', [ManualController::class, 'utilitiesForm'])->name('manual.utilities');
Route::get('/manual/tracking', [ManualController::class, 'trackingForm'])->name('manual.tracking');


/*
|--------------------------------------------------------------------------
| Routes for Tracking Certificates
|--------------------------------------------------------------------------
*/
// Route::get('/', [TrackingCertificateController::class, 'index'])->name('placemarks.index');

Route::get('/tracking-certificates/create', [TrackingCertificateController::class, 'create'])->name('tracking_certificates.create');
Route::post('/tracking-certificates/store', [TrackingCertificateController::class, 'store'])->name('tracking_certificates.store');
Route::post('/tracking-certificates/preview', [TrackingCertificateController::class, 'previewForm'])->name('manual.preview');
Route::post('/tracking-certificates/save-image', [TrackingCertificateController::class, 'saveImage'])->name('tracking_certificates.save_image');
Route::post('/tracking-certificates/save-temp-image', [TrackingCertificateController::class, 'saveTemporaryImage'])->name('tracking_certificates.save_temp_image');
Route::get('/tracking-certificates/{id}/edit', [TrackingCertificateController::class, 'edit'])->name('tracking_certificates.edit');
Route::put('tracking-certificates/{id}/update', [TrackingCertificateController::class, 'update'])->name('tracking_certificates.update');
Route::get('/tracking-certificates/create_from_existing/{id}', [TrackingCertificateController::class, 'createFromExisting'])->name('tracking_certificates.create_from_existing');
Route::get('/tracking-certificates/review/{status}', [TrackingCertificateController::class, 'reviewByStatus'])->name('tracking_certificates.review');
Route::get('/tracking-certificates/delivery/{status}', [TrackingCertificateController::class, 'deliveryByStatus'])->name('tracking_certificates.delivery');
Route::get('/tracking-certificates/stifaa', [TrackingCertificateController::class, 'stifaa'])->name('tracking_certificates.stifaa');
Route::post('tracking-certificates/store-from-existing', [TrackingCertificateController::class, 'storeFromExisting'])->name('tracking_certificates.storeFromExisting');
Route::post('/tracking-certificates/{id}/update-status', [TrackingCertificateController::class, 'updateStatus'])->name('tracking_certificates.update_status');
Route::get('/tracking/review', [TrackingCertificateController::class, 'reviewByStatus'])->name('tracking.review');
Route::get('/tracking/delivery', [TrackingCertificateController::class, 'deliveryByStatus'])->name('tracking.delivery');
Route::get('/tracking/stifaa', [TrackingCertificateController::class, 'stifaa'])->name('tracking.stifaa');
Route::get('/tracking-certificates/all', [TrackingCertificateController::class, 'all'])->name('tracking_certificates.all');
Route::post('/search', [TrackingCertificateController::class, 'searchByTransactionNumber'])->name('certificates.search');
Route::get('/search', [TrackingCertificateController::class, 'showSearchForm'])->name('certificates.search.form');
Route::get('/tracking-certificates/{id}/images', [TrackingCertificateController::class, 'showCertificateImages'])->name('certificates.showImages');
Route::delete('/tracking-certificates/{id}/images/delete', [TrackingCertificateController::class, 'deleteCertificateImage'])->name('certificates.deleteImage');


/*
|--------------------------------------------------------------------------
| Routes for Certificate Search & Edit
|--------------------------------------------------------------------------
*/
Route::get('/certificates/{type}/{id}/edit', [CertificateSearchController::class, 'edit'])->name('certificates.edit');
Route::post('/certificates/{type}/{id}/update', [CertificateSearchController::class, 'update'])->name('certificates.update');


/*
|--------------------------------------------------------------------------
| Routes for Transactions and Delivery Status
|--------------------------------------------------------------------------
*/
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::post('/transactions/{id}/deliver', [TransactionController::class, 'deliver'])->name('transactions.deliver');
Route::match(['get', 'post'], '/transactions/search', [TransactionController::class, 'search'])->name('transactions.search');
