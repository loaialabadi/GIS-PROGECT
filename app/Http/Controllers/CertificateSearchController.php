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

    // البحث في جميع أنواع الشهادات حسب رقم المعاملة
    public function searchByTransactionNumber(Request $request)
    {
        $request->validate([
            'transaction_number' => 'required|string',
        ]);

        $results = [];

        // البحث في كل نوع وإضافة أول نتيجة لكل نوع
        $results[] = \App\Models\TrackingCertificate::where('transaction_number', $request->transaction_number)->first();
        $results[] = \App\Models\UtilityCertificate::where('transaction_number', $request->transaction_number)->first();
        $results[] = \App\Models\SurveyCertificate::where('transaction_number', $request->transaction_number)->first();

        // إزالة القيم الفارغة
        $results = array_filter($results);

        if (empty($results)) {
            return redirect()->back()->with('error', 'لم يتم العثور على أي شهادة بهذا الرقم.');
        }

        return view('search.results', compact('results'));
    }










public function getCertificateImages($certificateId)
{
    $certificate = \App\Models\TrackingCertificate::findOrFail($certificateId);

    // اسم العميل بالعربي كما هو مخزن
    $clientName = $certificate->client_name;

    $folderPath = public_path("certificates/{$clientName}");

    $images = [];

    if (File::exists($folderPath)) {
        $files = File::files($folderPath);
        foreach ($files as $file) {
            $images[] = asset("certificates/{$clientName}/" . $file->getFilename());
        }
    }

    return view('search.certificate_images', compact('certificate', 'images'));
}
}
