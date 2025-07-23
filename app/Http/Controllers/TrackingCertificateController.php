<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrackingCertificate;

class TrackingCertificateController extends Controller
{
    // عرض الفورم
    public function create()
    {
        return view('tracking_certificates.create');
    }

    // حفظ البيانات في الداتابيس
    public function store(Request $request)
    {
        // تحقق من صحة البيانات
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'national_id' => 'required|string|max:50',
            'transaction_id' => 'required|string|max:100|unique:tracking_certificates,transaction_number',
            'building_description' => 'nullable|string',
            'project_name' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'tracking_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'inspector_name' => 'nullable|string|max:255',
        ]);

        TrackingCertificate::create($validated);

        return redirect()->route('tracking_certificates.create')->with('success', 'تم حفظ الشهادة بنجاح!');
    }
}
