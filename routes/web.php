<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlacemarkController;
use App\Models\Placemark;



// عرض البيانات من الكنترولر
Route::get('/', [PlacemarkController::class, 'index'])->name('placemarks.index');

// صفحة رفع الملف
Route::get('/placemarks/upload', [PlacemarkController::class, 'upload'])->name('placemarks.upload');

// معالجة رفع واستيراد ملف Excel
Route::post('/placemarks/import', [PlacemarkController::class, 'import'])->name('placemarks.import');

// توليد الشهادة من سجل محفوظ
Route::get('/certificate/{id}', [PlacemarkController::class, 'certificate'])->name('placemarks.certificate');

// توليد شهادة مؤقتة مباشرة من الصف المعروض (من Excel مباشرة)
Route::post('/placemarks/certificate/preview', [PlacemarkController::class, 'previewCertificate'])->name('placemarks.certificate.preview');


Route::post('/placemarks/certificate/generate', [PlacemarkController::class, 'generateCertificateImage'])->name('placemarks.certificate.generate');


Route::post('placemarks/certificate_image', [PlacemarkController::class, 'generateImageFromHTML'])->name('certificate.generate.image');


Route::get('/certificate/show/{filename}', [PlacemarkController::class, 'showCertificateImage'])
    ->name('placemarks.showCertificateImage');

