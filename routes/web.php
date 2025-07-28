<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlacemarkController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\TrackingCertificateController;
    use App\Http\Controllers\CertificateSearchController;

/*
|--------------------------------------------------------------------------
| Routes for Placemark Certificate System
|--------------------------------------------------------------------------
*/

// ✅ صفحة رفع ملف Excel
Route::get('/placemarks/upload', [PlacemarkController::class, 'upload'])
    ->name('placemarks.upload');

// ✅ معالجة استيراد ملف Excel
Route::post('/placemarks/import', [PlacemarkController::class, 'import'])
    ->name('placemarks.import');

// ✅ توليد شهادة مؤقتة مباشرة من البيانات
Route::post('/placemarks/certificate/preview', [PlacemarkController::class, 'previewCertificate'])
    ->name('placemarks.certificate.preview');

// ✅ توليد صورة الشهادة من HTML
Route::post('/placemarks/certificate_image', [PlacemarkController::class, 'generateImageFromHTML'])
    ->name('certificate.generate.image');

// ✅ عرض صورة الشهادة النهائية
Route::get('/certificate/show/{filename}', [PlacemarkController::class, 'showCertificateImage'])
    ->name('placemarks.showCertificateImage');

// ✅ حفظ البيانات في قاعدة البيانات
Route::post('/placemarks/save-data', [PlacemarkController::class, 'saveData'])
    ->name('placemarks.saveData');

// ✅ صفحة layout تجريبية (إن وُجدت)
Route::get('/layout', [PlacemarkController::class, 'layout'])
    ->name('layout');


Route::get('/manual/manual_form', [ManualController::class, 'manualForm'])->name('placemarks.manual');

Route::post('/placemarks/manual/generate', [PlacemarkController::class, 'generateManualCertificate'])->name('placemarks.manual.generate');



Route::get('/manual', [ManualController::class, 'chooseType'])->name('manual.choose');

Route::get('/manual/survey', [ManualController::class, 'surveyForm'])->name('manual.survey');
Route::get('/manual/utilities', [ManualController::class, 'utilitiesForm'])->name('manual.utilities');
Route::get('/manual/tracking', [ManualController::class, 'trackingForm'])->name('manual.tracking');



Route::get('/tracking-certificates/create', [TrackingCertificateController::class, 'create'])->name('tracking_certificates.create');
Route::post('/tracking-certificates/store', [TrackingCertificateController::class, 'store'])->name('tracking_certificates.store');


Route::post('/tracking-certificates/preview', [TrackingCertificateController::class, 'previewForm'])
    ->name('manual.preview');




Route::post('/tracking_certificates/{id}/save_pdf_path', [TrackingCertificateController::class, 'savePdfPath'])
    ->name('tracking_certificates.save_pdf_path');



    // ✅ البحث عن الشهادات حسب رقم المعاملة


Route::get('/search', [CertificateSearchController::class, 'showSearchForm'])->name('certificates.search.form');
Route::post('/search', [CertificateSearchController::class, 'searchByTransactionNumber'])->name('certificates.search');

Route::get('/certificates/{type}/{id}/edit', [CertificateSearchController::class, 'edit'])->name('certificates.edit');
Route::post('/certificates/{type}/{id}/update', [CertificateSearchController::class, 'update'])->name('certificates.update');
