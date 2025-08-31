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

        public function layout()
    {
        return view('layout');
    }
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
            'certificate_path' => 'nullable|string',
            'building_description' => 'nullable|string|max:255',
            'center_name' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'gis_preparer_name' => 'nullable|string|max:255',
            'gis_reviewer_name' => 'nullable|string|max:255',
            'tracking_status' => 'nullable|array',
            'inspector_name' => 'nullable|string|max:255',
            'selected_tracking' => 'nullable|array',
        ]);

        // معالجة المعاينة (preview)
        if ($request->input('action') === 'preview') {
            $data = $request->only([
                'client_name', 'national_id', 'transaction_number',
                'building_description', 'project_name', 'area',
                'purpose', 'coordinates', 'notes',
                'inspector_name', 'center_name', 'gis_preparer_name',
                'gis_reviewer_name', 'certificate_path'
            ]);

            $selectedTracking = $request->input('selected_tracking', []);
            $tracking = $request->input('tracking_status', []);

            // فلترة التواريخ التي تم اختيارها ولها حالة
            $selectedTracking = array_filter($selectedTracking, fn($d) => isset($tracking[$d]));

            // ترتيب التواريخ حسب الشهر
            uksort($tracking, function ($a, $b) {
                return strtotime('01-' . $a) <=> strtotime('01-' . $b);
            });

            $data['tracking_status'] = $tracking;
            $data['selected_tracking'] = $selectedTracking;

            return view('manual.tracking.preview', compact('data'));
        }

        try {
            // إنشاء أو جلب المعاملة بناءً على رقم المعاملة
            $transaction = Transaction::firstOrCreate([
                'transaction_number' => $validated['transaction_number'],
            ]);

            $validated['transaction_id'] = $transaction->id;

            // ترميز حالة التتبع والتواريخ المحددة
            $validated['tracking_status'] = $request->input('tracking_status', []);
            $validated['selected_tracking'] = $request->input('selected_tracking', []);

            // معالجة الصورة المؤقتة إذا وجدت
            if ($request->filled('certificate_path_temp')) {
                $tempPath = $request->input('certificate_path_temp'); 

                if (Storage::exists('public/' . $tempPath)) {
                    $folderName = $transaction->transaction_number;
                    $extension = pathinfo($tempPath, PATHINFO_EXTENSION) ?: 'jpg';
                    $finalPath = "certificates/{$folderName}/{$folderName}.{$extension}";

                    if (!Storage::exists('public/certificates/' . $folderName)) {
                        Storage::makeDirectory('public/certificates/' . $folderName, 0777, true);
                    }

                    Storage::move('public/' . $tempPath, 'public/' . $finalPath);
                    $validated['certificate_path'] = $finalPath;
                } else {
                    $validated['certificate_path'] = null;
                }
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
        ]);

        $transactionNumber = $request->input('transaction_number');
        $folder = "certificates/temp/{$transactionNumber}";
        $path = $request->file('image')->store($folder, 'public');

        return response()->json([
            'status' => 'success',
            'message' => '✅ تم حفظ الشهادة مؤقتًا',
            'path' => $path
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
        return redirect()->back()->with('success', 'تم تعديل بيانات الشهادة بنجاح');
    }

    public function updateStatus(Request $request, $id)
    {
        $certificate = TrackingCertificate::findOrFail($id);
        $status = (int) $request->input('status');
        $certificate->delivery_status = $status;

        if (in_array($status, [2, 4])) {
            $certificate->delivered_at = now();
        }

        $certificate->save();

        return response()->json([
            'success' => true,
            'newStatus' => $status
        ]);
    }

    public function filterByStatus(Request $request, array $statuses, $view, $statusLabel = null)
    {
        $query = TrackingCertificate::query()->whereIn('delivery_status', $statuses);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_number', 'like', "%$search%")
                  ->orWhere('client_name', 'like', "%$search%");
            });
        }

        $certificates = $query->get();

        return view($view, [
            'certificates' => $certificates,
            'status' => $statusLabel,
            'statuses' => $statuses,
            'search' => $request->search
        ]);
    }

    public function reviewByStatus(Request $request)
    {
        return $this->filterByStatus($request, [1, 3], 'manual.tracking.review', 'مراجعة');
    }

    public function deliveryByStatus(Request $request)
    {
        return $this->filterByStatus($request, [4, 5], 'manual.tracking.delivery', 'تسليم');
    }

    public function stifaa(Request $request)
    {
        return $this->filterByStatus($request, [2], 'manual.tracking.stifaa', 'استيفاء');
    }

    public function deleteImage(Request $request, $id)
    {
        $certificate = TrackingCertificate::findOrFail($id);
        $imagePath = $request->input('image');

        if (file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }

        return back()->with('success', 'تم حذف الصورة بنجاح');
    }

    public function createFromExisting($id)
    {
        $certificate = TrackingCertificate::findOrFail($id);

        $trackingStatus = is_string($certificate->tracking_status)
            ? json_decode($certificate->tracking_status, true)
            : $certificate->tracking_status;

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
            'gis_preparer_name' => 'nullable|string|max:255',
            'gis_reviewer_name' => 'nullable|string|max:255',
            'inspector_name' => 'nullable|string|max:255',
            'tracking_status' => 'nullable|array',
            'certificate_file' => 'nullable|image|max:2048',
        ]);

        try {
            $transaction = Transaction::firstOrCreate([
                'transaction_number' => $validated['transaction_number'],
            ]);
            $validated['transaction_id'] = $transaction->id;
            $validated['tracking_status'] = json_encode($validated['tracking_status'] ?? []);

            if ($request->hasFile('certificate_file')) {
                $file = $request->file('certificate_file');
                $finalFolder = 'certificates/' . $transaction->transaction_number . '_' . $validated['client_name'];
                $finalPath = $file->storeAs('public/' . $finalFolder, $file->getClientOriginalName());
                $validated['certificate_path'] = str_replace('public/', '', $finalPath);
            }

            TrackingCertificate::create($validated);

            return redirect()->route('manual.tracking')
                ->with('success', 'تم إنشاء الشهادة الجديدة بنجاح!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function showSearchForm()
    {
        return view('search.search');
    }

    public function searchByTransactionNumber(Request $request)
    {
        $request->validate([
            'transaction_number' => 'required|string',
        ]);

        $term = $request->input('transaction_number');

        $results = TrackingCertificate::where('transaction_number', $term)
                    ->orWhere('client_name', 'like', "%{$term}%")
                    ->get();

        return view('search.search', [
            'results' => $results,
        ]);
    }

    public function showCertificateImages($id)
    {
        $certificate = TrackingCertificate::findOrFail($id);

        $folderName = $certificate->transaction_number;
        $folderPath = storage_path('app/public/certificates/' . $folderName);

        if (!File::exists($folderPath) || count(File::files($folderPath)) === 0) {
            $folderPath = storage_path('app/public/certificates/temp/' . $folderName);
        }

        $images = [];
        if (File::exists($folderPath)) {
            foreach (File::files($folderPath) as $file) {
                $images[] = 'certificates/' 
                            . str_replace(storage_path('app/public/certificates/'), '', $folderPath) 
                            . '/' . $file->getFilename();
            }
        }

        return view('search.certificate_images', compact('certificate', 'images'));
    }

    public function deleteCertificateImage(Request $request, $id)
    {
        $certificate = TrackingCertificate::findOrFail($id);
        $image = $request->input('image');
        $fullPath = storage_path('app/public/' . $image);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
            return redirect()->back()->with('success', '✅ تم حذف الصورة بنجاح.');
        }

        return redirect()->back()->with('error', '❌ الصورة غير موجودة أو غير صالحة.');
    }

    public function all(Request $request)
    {
        $query = TrackingCertificate::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_number', 'like', "%$search%")
                  ->orWhere('client_name', 'like', "%$search%");
            });
        }

        $certificates = $query->get();

        return view('transactions.index', compact('certificates'));
    }


    public function destroy($id)
{
    // الحصول على السجل
    $certificate = TrackingCertificate::findOrFail($id);
    
    // حذف السجل
    $certificate->delete();
    
    // إعادة توجيه مع رسالة نجاح
    return redirect()->route('tracking_certificates.all')->with('success', 'تم حذف الشهادة بنجاح');
}
}
