<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrackingCertificate;
use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class TrackingCertificateController extends Controller
{
    public function create()
    {
        return view('tracking_certificates.create');
    }


    public function store(Request $request)
{
    // أولاً التحقق من البيانات
$validated = $request->validate([
    'client_name' => 'required|string|max:255',
    'national_id' => 'required|string|max:20',
    'transaction_number' => 'required|string|max:255',
    'building_description' => 'nullable|string|max:255',
    'center_name' => 'nullable|string|max:255',
    'area' => 'nullable|string|max:255',
    'notes' => 'nullable|string',
    'tracking_status' => 'nullable|array', // مصفوفة الحالة
    'inspector_name' => 'nullable|string|max:255',
]);


        if ($request->input('action') === 'preview') {
            $data = $request->only([
                'client_name', 'national_id', 'transaction_number',
                'building_description', 'project_name', 'area',
                 'notes', 'inspector_name', 'center_name',
            ]);

            // إضافة المصفوفة tracking_status التي تأتي من الفورم (مصفوفة)
            $data['tracking_status'] = $request->input('tracking_status', []);

            return view('manual.preview', compact('validated', 'data'));
        }


    try {
        // إنشاء أو جلب المعاملة
        $transaction = \App\Models\Transaction::firstOrCreate([
            'transaction_number' => $validated['transaction_number'],
        ]);

        // أضف transaction_id إلى البيانات
        $validated['transaction_id'] = $transaction->id;
    $validated['tracking_status'] = json_encode($request->input('tracking_status', []));

        // حفظ شهادة المتابعة
        \App\Models\TrackingCertificate::create($validated);

        return redirect()->route('manual.tracking')
            ->with('success', 'تم حفظ شهادة المتابعة بنجاح!');
    } catch (QueryException $e) {
        if ($e->errorInfo[1] == 1062) {
            // خطأ تكرار المفتاح الفريد
            return back()->withErrors([
                'transaction_number' => '⚠️ رقم المعاملة مستخدم بالفعل، برجاء اختيار رقم مختلف.'
            ])->withInput();
        }

        // إعادة رمي الخطأ في حال كان مختلف
        throw $e;
    }
}






public function savePdfPath($id)
{
    $certificate = TrackingCertificate::findOrFail($id);

    // توليد ملف PDF بناءً على بيانات الشهادة
    $pdf = \PDF::loadView('manual.preview_pdf', ['data' => $certificate]);

    // مسار الحفظ داخل storage/app/certificates
    $filename = 'certificate_' . $certificate->transaction_number . '.pdf';
    $path = storage_path('app/certificates/' . $filename);

    // تأكد أن المجلد موجود
    if (!file_exists(storage_path('app/certificates'))) {
        mkdir(storage_path('app/certificates'), 0755, true);
    }

    // حفظ الملف
    $pdf->save($path);

    // تحديث المسار في قاعدة البيانات (تأكد أن لديك حقل مثل pdf_path)
    $certificate->pdf_path = 'certificates/' . $filename;
    $certificate->save();

    return redirect()->back()->with('success', 'تم حفظ نسخة الشهادة بنجاح!');
}


}
