<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Placemark;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class PlacemarkController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | تحميل واستعراض ملف Excel
    |--------------------------------------------------------------------------
    */

    // صفحة رفع ملف Excel
    public function upload()
    {
        return view('placemarks.upload');
    }

    // قراءة الملف واستعراض البيانات بدون حفظ

public function import(Request $request)
{
    $request->validate([
        'excel_file' => 'required|file|mimes:xlsx,xls',
    ]);

    $rows = Excel::toArray([], $request->file('excel_file'));
    $data = $rows[0] ?? [];

    $headers = array_map('trim', $data[0] ?? []);
    $records = array_slice($data, 1);

    // ✅ تحويل الأرقام العربية في كل الخلايا
    $convertedRecords = array_map(function ($row) {
        return array_map([$this, 'convertArabicToEnglishNumbers'], $row);
    }, $records);

    return view('placemarks.display', [
        'headers' => $headers,
        'records' => $convertedRecords,
    ]);
}

private function convertArabicToEnglishNumbers($string)
{
    $arabic = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
    $english = ['0','1','2','3','4','5','6','7','8','9'];
    return str_replace($arabic, $english, $string);
}

    /*
    |--------------------------------------------------------------------------
    | توليد الشهادة من البيانات
    |--------------------------------------------------------------------------
    */

    // عرض نموذج الشهادة للتعديل قبل التوليد
    public function previewCertificate(Request $request)
    {
        $data = $request->input('data');
        return view('placemarks.certificate_form', ['placemark' => $data]);
    }
public function generateImageFromHTML(Request $request)
{
    $data = $request->input('data', []);

    // حفظ الصور المرفوعة (لو في)
    for ($i = 1; $i <= 4; $i++) {
        $fileKey = "image_$i";
        if ($request->hasFile($fileKey) && $request->file($fileKey)->isValid()) {
            $path = $request->file($fileKey)->store('certificates', 'public');
            $data["image{$i}"] = asset('storage/' . $path); // استخدم رابط مباشر
        }
    }

    $html = view('placemarks.certificate_image', compact('data'))->render();

    $filename = 'certificate_' . time() . '.png';
    $path = public_path('certificates/' . $filename);

    Browsershot::html($html)
        ->format('A3')
        ->margins(0, 0, 0, 0)
        ->windowSize(2480, 3508)
        ->save($path);

    return redirect()->route('placemarks.showCertificateImage', ['filename' => $filename]);
}



    // عرض صورة الشهادة بعد التوليد
public function showCertificateImage($filename)
{
    $imageUrl = asset('certificates/' . $filename);
    return view('placemarks.show_certificate_image', compact('imageUrl'));
}


    /*
    |--------------------------------------------------------------------------
    | حفظ البيانات في قاعدة البيانات
    |--------------------------------------------------------------------------
    */

    public function saveData(Request $request)
    {
        $data = $request->input('data');

        $placemark = new Placemark();

        // الحقول النصية
        $placemark->name         = $data['name'] ?? $data['الاسم'] ?? null;
        $placemark->national_id  = $data['national_id'] ?? $data['رقم بطاقة المواطن'] ?? null;
        $placemark->area_m2      = $data['area_m2'] ?? $data['المساحة'] ?? null;
        $placemark->address      = $data['address'] ?? $data['عنوان القطعة'] ?? null;
        $placemark->description  = $data['description'] ?? $data['الوصف'] ?? null;
        $placemark->supplier     = $data['supplier'] ?? $data['جهة التوريد'] ?? null;
        $placemark->geometry     = $data['geometry'] ?? null;
        $placemark->notes        = $data['notes'] ?? $data['ملاحظات'] ?? null;
        $placemark->inspector    = $data['inspector'] ?? $data['المفتش'] ?? null;

        // الصور (image1 إلى image8)
        for ($i = 1; $i <= 8; $i++) {
            $key = "image{$i}";
            $placemark->$key = $data[$key] ?? $data["صورة {$i}"] ?? null;
        }

        $placemark->save();

        return redirect()->back()->with('success', '✅ تم حفظ البيانات في قاعدة البيانات بنجاح.');
    }

    /*
    |--------------------------------------------------------------------------
    | عرض صفحة layout تجريبية (اختياري)
    |--------------------------------------------------------------------------
    */

    public function layout()
    {
        return view('layout');
    }

    



public function generateManualCertificate(Request $request)
{
    $data = $request->input('data'); // بيانات الشهادة المدخلة

    return view('placemarks.certificate_image', [
        'data' => $data,
        'filename' => null, // أو ضع اسم عشوائي لو تحفظ الصورة لاحقاً
    ]);
}

}
