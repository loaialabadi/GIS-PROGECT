<?php
namespace App\Imports;

use App\Models\Placemark;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class PlacemarkImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // نتجاهل الصفوف الفارغة (مثلاً بدون اسم أو إحداثيات)
    if (
        empty(trim($row['الاسم'] ?? null)) &&
        empty(trim($row['name'] ?? null)) &&
        empty(trim($row['Geometry'] ?? null)) &&
        empty(trim($row['geometry'] ?? null))
    ) {
        return null;
    }


return new Placemark([
    'Name'                => $row['الاسم'] ?? $row['name'] ?? $row['Name'] ?? null,
    'national_id'         => $row['رقم بطاقة المواطن'] ?? null,
    'area_m2'             => $row['مساحة القطعة بالمتر المربع'] ?? null,
    'address'             => $row['عنوان القطعة'] ?? null,
    'description'         => $row['وصف القطعة'] ?? null,
    'supplier'            => $row['جهة التوريد'] ?? null,
    'north_border'        => $row['الحد البحرى'] ?? null,
    'south_border'        => $row['الحد القبلى'] ?? null,
    'east_border'         => $row['الحد الشرقى'] ?? null,
    'west_border'         => $row['الحد الغربى'] ?? null,
    'geometry'            => $row['geometry'] ?? $row['Geometry'] ?? null,

    'image8'              => $row['صورة ٨'] ?? null,
    'image7'              => $row['صورة ٧'] ?? null,
    'image6'              => $row['صورة ٦'] ?? null,
    'image5'              => $row['صورة ٥'] ?? null,
    'image4'              => $row['صورة ٤'] ?? null,
    'image3'              => $row['صورة ٣'] ?? null,
    'image2'              => $row['صورة ٢'] ?? null,
    'image1'              => $row['صورة ١'] ?? null,

    'notes'               => $row['ملاحظات'] ?? null,
    'inspector'           => $row['القائم بالمعاينة'] ?? null,
]);

    }
}
