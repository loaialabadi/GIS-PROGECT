<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;

class CertificateSearchController extends Controller
{
    // عرض صفحة نموذج البحث
    public function showSearchForm()
    {
        return view('search.search');
    }

    // عرض صفحة تعديل شهادة حسب النوع والمعرف
    public function edit($type, $id)
    {
        $modelClass = $this->resolveModelClass($type);
        $certificate = $modelClass::findOrFail($id);

        return view('search.edit', compact('certificate', 'type'));
    }

    // تحديث بيانات الشهادة بعد التعديل
    public function update(Request $request, $type, $id)
    {
        $modelClass = $this->resolveModelClass($type);
        $certificate = $modelClass::findOrFail($id);

        $data = $request->except('_token');

        // إذا كان هناك حقل JSON داخلي لتتبع الحالة، نحوله إلى JSON
        if ($request->has('tracking_status')) {
            $data['tracking_status'] = json_encode($request->tracking_status);
        }

        $certificate->update($data);

        return redirect()->route('certificates.search.form')->with('success', 'تم التعديل بنجاح.');
    }

    // دالة لحل اسم الكلاس بناءً على نوع الشهادة (tracking, utility, survey)
    protected function resolveModelClass($type)
    {
        return match (strtolower($type)) {
            'trackingcertificate' => \App\Models\TrackingCertificate::class,
            'utilitycertificate' => \App\Models\UtilityCertificate::class,
            'surveycertificate' => \App\Models\SurveyCertificate::class,
            default => abort(404, 'نوع الشهادة غير معروف'),
        };
    }









    public function showCertificateImages($certificateId)
    {
        $certificate = TrackingCertificate::findOrFail($certificateId);

        // المسار داخل storage/app/public
        $folderPath = storage_path('app/public/certificates/' . $certificate->transaction_number . '_' . $certificate->client_name);

        $images = [];

        if (File::exists($folderPath)) {
            $files = File::files($folderPath);
            foreach ($files as $file) {
                $images[] = 'storage/certificates/' . $certificate->transaction_number . '_' . $certificate->client_name . '/' . $file->getFilename();
            }
        }

        return view('tracking_certificates.images', compact('certificate', 'images'));
    }

    // حذف صورة معينة
    public function deleteCertificateImage(Request $request, $certificateId)
    {
        $certificate = TrackingCertificate::findOrFail($certificateId);
        $imagePath = $request->input('image'); // المسار مثل: storage/certificates/147_name/file.png

        if (Storage::exists(str_replace('storage/', 'public/', $imagePath))) {
            Storage::delete(str_replace('storage/', 'public/', $imagePath));
        }

        return back()->with('success', 'تم حذف الصورة بنجاح!');
    }
}
