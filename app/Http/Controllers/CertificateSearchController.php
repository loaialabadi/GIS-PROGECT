<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificateSearchController extends Controller
{




public function showSearchForm()
{
    return view('search.search');
}

public function edit($type, $id)
{
    $modelClass = $this->resolveModelClass($type);
    $certificate = $modelClass::findOrFail($id);

    return view('search.edit', compact('certificate', 'type'));
}


public function update(Request $request, $type, $id)
{
    $modelClass = $this->resolveModelClass($type);
    $certificate = $modelClass::findOrFail($id);

    $data = $request->except('_token');

    // لو فيه JSON داخلي
    if ($request->has('tracking_status')) {
        $data['tracking_status'] = json_encode($request->tracking_status);
    }

    $certificate->update($data);

    return redirect()->route('certificates.search.form')->with('success', 'تم التعديل بنجاح.');
}


protected function resolveModelClass($type)
{
    return match (strtolower($type)) {
        'trackingcertificate' => \App\Models\TrackingCertificate::class,
        'utilitycertificate' => \App\Models\UtilityCertificate::class,
        'surveycertificate' => \App\Models\SurveyCertificate::class,
        default => abort(404, 'نوع الشهادة غير معروف'),
    };
}



 public function searchByTransactionNumber(Request $request)
{
    $request->validate([
        'transaction_number' => 'required|string',
    ]);

    // البحث في كل أنواع الشهادات
    $results = [];

    $results[] = \App\Models\TrackingCertificate::where('transaction_number', $request->transaction_number)->first();
    $results[] = \App\Models\UtilityCertificate::where('transaction_number', $request->transaction_number)->first();
    $results[] = \App\Models\SurveyCertificate::where('transaction_number', $request->transaction_number)->first();

    $results = array_filter($results); // حذف النتائج الفارغة

    if (empty($results)) {
        return redirect()->back()->with('error', 'لم يتم العثور على أي شهادة بهذا الرقم.');
    }

    return view('search.results', compact('results'));
}

}
