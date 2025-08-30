<?php

namespace App\Exports;

use App\Models\TrackingCertificate;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TrackingCertificatesExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = TrackingCertificate::query();

        // فلترة بالاسم
        if ($this->request->client_name) {
            $query->where('client_name', 'like', '%' . $this->request->client_name . '%');
        }

        // فلترة بالتواريخ
        if ($this->request->from && $this->request->to) {
            $query->whereBetween('created_at', [$this->request->from, $this->request->to]);
        }

        // فلترة بأسماء المفتش و GIS
        if ($this->request->inspector_name) {
            $query->where('inspector_name', 'like', '%' . $this->request->inspector_name . '%');
        }

        if ($this->request->gis_preparer_name) {
            $query->where('gis_preparer_name', 'like', '%' . $this->request->gis_preparer_name . '%');
        }

        if ($this->request->gis_reviewer_name) {
            $query->where('gis_reviewer_name', 'like', '%' . $this->request->gis_reviewer_name . '%');
        }

        return $query->get([
            'transaction_number',
            'client_name',
            'national_id',
            'center_name',
            'purpose',
            'area',
            'inspector_name',
            'gis_preparer_name',
            'gis_reviewer_name',
            'created_at',
        ]);
    }

    public function headings(): array
    {
        return [
            'رقم المعاملة',
            'اسم العميل',
            'الرقم القومي',
            'المركز',
            'الغرض',
            'المساحة',
            'المفتش',
            'مُعد GIS',
            'مراجع GIS',
            'تاريخ الإنشاء',
        ];
    }
}
