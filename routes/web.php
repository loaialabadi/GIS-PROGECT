<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlacemarkController;

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


