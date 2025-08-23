<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrackingCertificate;
use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TrackingCertificateController extends Controller
{
    // عرض صفحة إنشاء شهادة تتبع جديدة
    public function create()
    {
        return view('tracking_certificates.create');
    }

public function store(Request $request)
{
    // التحقق من صحة البيانات المرسلة
    $validated = $request->validate([
        'client_name' => 'required|string|max:255',
        'national_id' => 'required|string|max:20',
        'transaction_number' => 'required|string|max:255',
        'purpose' => 'required|string|max:500',
        'coordinates' => 'nullable|string|max:255',

        'certificate_path' => 'nullable|string', // مسار الصورة المؤقتة

        'building_description' => 'nullable|string|max:255',
        'center_name' => 'nullable|string|max:255',
        'area' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
        'gis_name' => 'nullable|string|max:255',
        'tracking_status' => 'nullable|array', // مصفوفة حالة التتبع
        'inspector_name' => 'nullable|string|max:255',
        'selected_tracking' => 'nullable|array', // التواريخ المختارة
    ]);

    // إذا كانت العملية معاينة (preview)
    if ($request->input('action') === 'preview') {
        $data = $request->only([
            'client_name', 'national_id', 'transaction_number',
            'building_description', 'project_name', 'area',
            'purpose', 'coordinates',
            'notes', 'inspector_name', 'center_name',
            'gis_name','certificate_path'
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

        // ترميز حالة التتبع
        $validated['tracking_status'] = json_encode($request->input('tracking_status', []));
        $validated['selected_tracking'] = json_encode($request->input('selected_tracking', []));


if (!empty($request->input('certificate_path_temp'))) {
    $tempPath = $request->input('certificate_path_temp'); 
    $finalFolder = 'certificates/' . $transaction->transaction_number . '_' . $validated['client_name'];
    $finalPath = $finalFolder . '/' . basename($tempPath);

    if (!Storage::exists('public/' . $finalFolder)) {
        Storage::makeDirectory('public/' . $finalFolder, 0777, true);
    }

    Storage::move('public/' . $tempPath, 'public/' . $finalPath);
    $validated['certificate_path'] = $finalPath; // هذا هو المسار النهائي المرتبط بالمعاملة
}

        // إنشاء سجل جديد لشهادة التتبع
        TrackingCertificate::create($validated);

        return redirect()->route('manual.tracking')
            ->with('success', '✅ تم حفظ شهادة المتابعة والصورة بنجاح!');

    } catch (QueryException $e) {
        if ($e->errorInfo[1] == 1062) {
            return back()->withErrors([
                'transaction_number' => '⚠️ رقم المعاملة مستخدم بالفعل، برجاء اختيار رقم مختلف.'
            ])->withInput();
        }
        throw $e;
    }
}

public function saveTemporaryImage(Request $request)
{
    $request->validate([
        'image' => 'required|image',
        'transaction_number' => 'required',
        'client_name' => 'required',
    ]);

    $transactionNumber = $request->input('transaction_number');
    $clientName = str_replace(['/', '\\', ' '], '_', $request->input('client_name'));

    // إنشاء اسم المجلد بناءً على رقم المعاملة واسم العميل
    $folder = "certificates/{$transactionNumber}_{$clientName}";

    // حفظ الصورة مؤقتًا داخل storage/app/public
    $path = $request->file('image')->store($folder, 'public');

    return response()->json([
        'status' => 'success',
        'message' => '✅ تم حفظ الشهادة مؤقتًا',
        'path' => Storage::url($path) // رابط للوصول للصورة من public/storage
    ]);
}
public function edit($id)
{
    $certificate = TrackingCertificate::findOrFail($id);
    return view('manual.tracking.edit', compact('certificate'));
}

public function update(Request $request, $id)
{
    $certificate = TrackingCertificate::findOrFail($id);

    $certificate->update($request->all());

    return redirect()->route('transactions.index')->with('success', 'تم تعديل بيانات الشهادة بنجاح');
}

public function updateStatus(Request $request, $id)
{
    $certificate = \App\Models\TrackingCertificate::findOrFail($id);

    $change = $request->input('change', 0); // 1 أو -1
    $newStatus = $certificate->delivery_status + $change;

    // تحديد الحد الأدنى والحد الأقصى للقيمة 1 إلى 7
    if ($newStatus < 1) $newStatus = 1;
    if ($newStatus > 7) $newStatus = 7;

    $certificate->delivery_status = $newStatus;

    // لو تحب ممكن تضيف شرط لتسجيل وقت عند القيمة 7
    if ($newStatus === 7) {
        $certificate->delivered_at = now();
    }

    $certificate->save();

    return response()->json([
        'success' => true,
        'newStatus' => $newStatus,
    ]);
}


public function reviewByStatus($status)
{
    $statuses = [1, 2, 4]; // القيم اللي عايز تجيبها
    $certificates = \App\Models\TrackingCertificate::whereIn('delivery_status', $statuses)->get();

    return view('manual.tracking.review', compact('certificates', 'statuses'));
}

public function deliveryByStatus($status)
{
     $statuses = [2, 3, 4]; // القيم اللي عايز تجيبها
    $certificates = \App\Models\TrackingCertificate::whereIn('delivery_status', $statuses)->get();

    return view('manual.tracking.delivery', compact('certificates', 'status'));
}

public function stifaa()
{
    // نفترض أن الاستيفاء حالته رقم 3 مثلاً
    $status = "استيفاء";
    $certificates = TrackingCertificate::where('delivery_status', 3)->get();

    return view('manual.tracking.stifaa', compact('certificates', 'status'));
}

public function deleteImage(Request $request, $id)
{
    $certificate = TrackingCertificate::findOrFail($id);
    $imagePath = $request->input('image');

    if (file_exists(public_path($imagePath))) {
        unlink(public_path($imagePath));
    }

    // حذف الرابط من الـ DB لو انت مخزن الصور في JSON أو جدول
    // مثال: 
    // $certificate->images = array_filter($certificate->images, fn($img) => $img !== $imagePath);
    // $certificate->save();

    return back()->with('success', 'تم حذف الصورة بنجاح');
}

public function createFromExisting($id)
{
    $certificate = TrackingCertificate::findOrFail($id);

    // فك JSON التواريخ إذا كانت موجودة
    $trackingStatus = is_string($certificate->tracking_status)
        ? json_decode($certificate->tracking_status, true)
        : $certificate->tracking_status;

        $trackingStatus = collect($trackingStatus)
    ->filter(function($status, $date) {
        return !empty($date) && !empty($status);
    });


    // تمرير البيانات إلى view إنشاء شهادة جديدة
    return view('manual.tracking.create_from_existing', [
        'data' => $certificate,
        'trackingStatus' => $trackingStatus,
    ]);
}

public function storeFromExisting(Request $request)
{
    $validated = $request->validate([
        'client_name' => 'required|string|max:255',
        'national_id' => 'required|string|max:20',
        'transaction_number' => 'required|string|max:255',
        'purpose' => 'required|string|max:500',
        'coordinates' => 'nullable|string|max:255',
        'building_description' => 'nullable|string|max:255',
        'center_name' => 'nullable|string|max:255',
        'area' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
        'gis_name' => 'nullable|string|max:255',
        'inspector_name' => 'nullable|string|max:255',
        'tracking_status' => 'nullable|array',
        'certificate_file' => 'nullable|image|max:2048', // رفع صورة جديدة
    ]);

    try {
        // إنشاء أو جلب المعاملة
        $transaction = Transaction::firstOrCreate([
            'transaction_number' => $validated['transaction_number'],
        ]);
        $validated['transaction_id'] = $transaction->id;

        // ترميز التواريخ
        $validated['tracking_status'] = json_encode($validated['tracking_status'] ?? []);

        // رفع الصورة إذا تم اختيارها
        if ($request->hasFile('certificate_file')) {
            $file = $request->file('certificate_file');
            $finalFolder = 'certificates/' . $transaction->transaction_number . '_' . $validated['client_name'];
            $finalPath = $file->storeAs('public/' . $finalFolder, $file->getClientOriginalName());
            $validated['certificate_path'] = str_replace('public/', '', $finalPath);
        }

        // إنشاء سجل جديد للشهادة
        TrackingCertificate::create($validated);

        return redirect()->route('manual.tracking')
            ->with('success', 'تم إنشاء الشهادة الجديدة بنجاح!');

    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()])->withInput();
    }
}





    
}
