<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrackingCertificate;
use App\Exports\TrackingCertificatesExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
{
    $query = TrackingCertificate::query();

    // فلترة بالاسم
    if ($request->name) {
        $query->where('client_name', 'like', '%' . $request->name . '%');
    }

    // فلترة بالتاريخ
    if ($request->from && $request->to) {
        $query->whereBetween('created_at', [$request->from, $request->to]);
    }

    // فلترة بالمفتش
    if ($request->inspector_name) {
        $query->where('inspector_name', 'like', '%' . $request->inspector_name . '%');
    }

    // فلترة بمُعد GIS
    if ($request->gis_preparer_name) {
        $query->where('gis_preparer_name', 'like', '%' . $request->gis_preparer_name . '%');
    }

    // فلترة بمراجع GIS
    if ($request->gis_reviewer_name) {
        $query->where('gis_reviewer_name', 'like', '%' . $request->gis_reviewer_name . '%');
    }

    $certificates = $query->paginate(15);

    return view('reports.index', compact('certificates'));
}


    public function export(Request $request)
    {
    return Excel::download(new TrackingCertificatesExport($request), 'tracking_certificates.xlsx');

    }
}
