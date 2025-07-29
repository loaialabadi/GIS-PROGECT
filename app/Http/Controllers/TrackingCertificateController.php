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
    // عرض صفحة إنشاء شهادة تتبع جديدة
    public function create()
    {
        return view('tracking_certificates.create');
    }

    // حفظ شهادة التتبع أو معاينة قبل الحفظ
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المرسلة
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'national_id' => 'required|string|max:20',
            'transaction_number' => 'required|string|max:255',
            'building_description' => 'nullable|string|max:255',
            'center_name' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'gis_name' => 'nullable|string|max:255',
            'tracking_status' => 'nullable|array', // مصفوفة حالة التتبع
            'inspector_name' => 'nullable|string|max:255',
        ]);

        // إذا كانت العملية معاينة (preview)
        if ($request->input('action') === 'preview') {
            $data = $request->only([
                'client_name', 'national_id', 'transaction_number',
                'building_description', 'project_name', 'area',
                'notes', 'inspector_name', 'center_name',
                'gis_name'
            ]);

            // جلب التواريخ المحددة وحالة التتبع
            $selectedTracking = $request->input('selected_tracking', []);
            $tracking = $request->input('tracking_status', []);

            // فلترة التواريخ التي تم اختيارها فقط والتي لها حالة
            $selectedTracking = array_filter($selectedTracking, fn($d) => isset($tracking[$d]));

            // ترتيب التواريخ حسب الشهر (افتراضي 01-الشهر)
            uksort($tracking, function ($a, $b) {
                return strtotime('01-' . $a) <=> strtotime('01-' . $b);
            });

            $data['tracking_status'] = $tracking;
            $data['selected_tracking'] = $selectedTracking;

            // عرض صفحة المعاينة مع البيانات
            return view('manual.tracking.preview', compact('data'));
        }

        try {
            // إنشاء أو جلب المعاملة بناءً على رقم المعاملة
            $transaction = Transaction::firstOrCreate([
                'transaction_number' => $validated['transaction_number'],
            ]);

            // ربط المعاملة بالبيانات
            $validated['transaction_id'] = $transaction->id;

            // ترميز حالة التتبع إلى JSON للحفظ في قاعدة البيانات
            $validated['tracking_status'] = json_encode($request->input('tracking_status', []));

            // إنشاء سجل جديد لشهادة التتبع
            TrackingCertificate::create($validated);

            return redirect()->route('manual.tracking')
                ->with('success', 'تم حفظ شهادة المتابعة بنجاح!');
        } catch (QueryException $e) {
            // معالجة خطأ التكرار على رقم المعاملة
            if ($e->errorInfo[1] == 1062) {
                return back()->withErrors([
                    'transaction_number' => '⚠️ رقم المعاملة مستخدم بالفعل، برجاء اختيار رقم مختلف.'
                ])->withInput();
            }

            // في حال خطأ آخر، إعادة رمي الخطأ
            throw $e;
        }
    }

    // حفظ مسار ملف الـ PDF بعد توليده
    public function savePdfPath($id)
    {
        $certificate = TrackingCertificate::findOrFail($id);

        // توليد ملف PDF من عرض Blade
        $pdf = Pdf::loadView('manual.preview_pdf', ['data' => $certificate]);

        // مسار حفظ الملف داخل مجلد storage/app/certificates
        $filename = 'certificate_' . $certificate->transaction_number . '.pdf';
        $path = storage_path('app/certificates/' . $filename);

        // التأكد من وجود المجلد أو إنشاؤه
        if (!file_exists(storage_path('app/certificates'))) {
            mkdir(storage_path('app/certificates'), 0755, true);
        }

        // حفظ ملف الـ PDF في المسار المحدد
        $pdf->save($path);

        // تحديث مسار ملف الـ PDF في قاعدة البيانات
        $certificate->pdf_path = 'certificates/' . $filename;
        $certificate->save();

        return redirect()->back()->with('success', 'تم حفظ نسخة الشهادة بنجاح!');
    }
}
