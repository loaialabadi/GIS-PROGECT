<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlacemarkController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\TrackingCertificateController;
use App\Http\Controllers\CertificateSearchController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Routes for Placemark Certificate System
|--------------------------------------------------------------------------
*/
// ✅ الصفحة الرئيسية لنظام الشهادات
Route::get('/', [PlacemarkController::class, 'index'])
    ->name('placemarks.index');

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



/*
|--------------------------------------------------------------------------
| Routes for Manual Certificates
|--------------------------------------------------------------------------
*/

// ✅ نموذج إدخال يدوي عام
Route::get('/manual/manual_form', [ManualController::class, 'manualForm'])
    ->name('placemarks.manual');

// ✅ حفظ الشهادة اليدوية
Route::post('/placemarks/manual/generate', [PlacemarkController::class, 'generateManualCertificate'])
    ->name('placemarks.manual.generate');

// ✅ اختيار نوع الشهادة اليدوية
Route::get('/manual', [ManualController::class, 'chooseType'])
    ->name('manual.choose');

// ✅ النماذج الثلاثة اليدوية
Route::get('/manual/survey', [ManualController::class, 'surveyForm'])->name('manual.survey');
Route::get('/manual/utilities', [ManualController::class, 'utilitiesForm'])->name('manual.utilities');
Route::get('/manual/tracking', [ManualController::class, 'trackingForm'])->name('manual.tracking');



/*
|--------------------------------------------------------------------------
| Routes for Tracking Certificates
|--------------------------------------------------------------------------
*/

// ✅ إنشاء شهادة تتبع زمني
Route::get('/tracking-certificates/create', [TrackingCertificateController::class, 'create'])
    ->name('tracking_certificates.create');

// ✅ حفظ شهادة التتبع
Route::post('/tracking-certificates/store', [TrackingCertificateController::class, 'store'])
    ->name('tracking_certificates.store');

// ✅ معاينة نموذج الشهادة
Route::post('/tracking-certificates/preview', [TrackingCertificateController::class, 'previewForm'])
    ->name('manual.preview');

// ✅ حفظ مسار PDF بعد إنشائه
// Route::post('/tracking-certificates/save-image', [TrackingCertificateController::class, 'saveImage'])
//     ->name('tracking_certificates.save_image');

Route::post('/tracking-certificates/save-image', [TrackingCertificateController::class, 'saveImage'])
    ->name('tracking_certificates.save_image');


    Route::post('/tracking-certificates/save-temp-image', [TrackingCertificateController::class, 'saveTemporaryImage'])->name('tracking_certificates.save_temp_image');

    Route::get('/tracking-certificates/{id}/edit', [TrackingCertificateController::class, 'edit'])->name('tracking_certificates.edit');
Route::put('tracking-certificates/{id}/update', [TrackingCertificateController::class, 'update'])->name('tracking_certificates.update');



// صفحة مراجعة الشهادات حسب الحالة
Route::get('/tracking-certificates/review/{status}', [TrackingCertificateController::class, 'reviewByStatus'])
    ->name('tracking_certificates.review');

// صفحة عرض التسليمات التي تحتوي على رقم معين
Route::get('/tracking-certificates/delivery/{status}', [TrackingCertificateController::class, 'deliveryByStatus'])
    ->name('tracking_certificates.delivery');


    Route::get('/tracking-certificates/stifaa', [TrackingCertificateController::class, 'stifaa'])
    ->name('tracking_certificates.stifaa');


Route::post('tracking-certificates/store-from-existing', [TrackingCertificateController::class, 'storeFromExisting'])
    ->name('tracking_certificates.storeFromExisting');

/*

|--------------------------------------------------------------------------
| Routes for Certificate Search & Edit
|--------------------------------------------------------------------------
*/

// ✅ عرض نموذج البحث عن الشهادات برقم المعاملة
Route::get('/search', [CertificateSearchController::class, 'showSearchForm'])
    ->name('certificates.search.form');

// ✅ تنفيذ البحث
Route::post('/search', [CertificateSearchController::class, 'searchByTransactionNumber'])
    ->name('certificates.search');

// ✅ تعديل الشهادة حسب النوع والمعرّف
Route::get('/certificates/{type}/{id}/edit', [CertificateSearchController::class, 'edit'])
    ->name('certificates.edit');

// ✅ تحديث الشهادة بعد التعديل
Route::post('/certificates/{type}/{id}/update', [CertificateSearchController::class, 'update'])
    ->name('certificates.update');

Route::get('tracking-certificates/{id}/images', [CertificateSearchController::class, 'getCertificateImages'])
    ->name('tracking_certificates.images');



    Route::post('/tracking-certificates/{id}/update-status', [TrackingCertificateController::class, 'updateStatus'])
    ->name('tracking_certificates.update_status');


Route::delete('/certificates/{id}/delete-image', [TrackingCertificateController::class, 'deleteImage'])
    ->name('certificates.deleteImage');





/*
|--------------------------------------------------------------------------
| Routes for Transactions and Delivery Status
|--------------------------------------------------------------------------
*/

// ✅ عرض كل المعاملات
Route::get('/transactions', [TransactionController::class, 'index'])
    ->name('transactions.index');

// ✅ تحديث حالة التسليم (مرحلة واحدة أو أكثر)
Route::post('/transactions/{id}/deliver', [TransactionController::class, 'deliver'])
    ->name('transactions.deliver');

// ✅ البحث عن المعاملة برقمها (يدعم GET و POST)
Route::match(['get', 'post'], '/transactions/search', [TransactionController::class, 'search'])
    ->name('transactions.search');
