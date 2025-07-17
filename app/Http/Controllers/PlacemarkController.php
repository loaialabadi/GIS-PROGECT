<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use App\Models\Placemark;
use Intervention\Image\Facades\Image;
use Spatie\Browsershot\Browsershot;

class PlacemarkController extends Controller
{
    // الصفحة الرئيسية لرفع الملف
    public function index()
    {
        return view('placemarks.upload');
    }

    // صفحة رفع ملف Excel
    public function upload()
    {
        return view('placemarks.upload');
    }

    // قراءة الملف وعرض البيانات بدون تخزين
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        $rows = Excel::toArray([], $request->file('excel_file'));
        $data = $rows[0] ?? [];

        $headers = array_map('trim', $data[0] ?? []);
        $records = array_slice($data, 1);

        return view('placemarks.display', compact('headers', 'records'));
    }



    // عرض نموذج الشهادة (قبل التوليد)
  public function previewCertificate(Request $request)
   {
       $data = $request->input('data');
        return view('placemarks.certificate_form', ['placemark' => $data]);
   }

    // // توليد شهادة PDF
    // public function certificate($id)
    // {
    //     $placemark = Placemark::findOrFail($id);

    //     $pdf = PDF::loadView('placemarks.certificate', ['placemark' => $placemark])
    //               ->setPaper('a3', 'landscape');

    //     return $pdf->stream('certificate_'.$placemark->name.'.pdf');
    // }

    // ✅ توليد صورة باستخدام Intervention Image مع خلفية صورة
    // public function generateCertificateImage(Request $request)
    // {
    //     $data = $request->input('data');

    //     $img = Image::make(public_path('images/certificate_bg.png'));

    //     // إضافة الاسم
    //     $img->text($data['الاسم'] ?? '', 1000, 800, function($font) {
    //         $font->file(public_path('fonts/arial.ttf'));
    //         $font->size(48);
    //         $font->color('#000');
    //         $font->align('right');
    //         $font->valign('top');
    //     });

    //     // إضافة رقم البطاقة
    //     $img->text($data['رقم بطاقة المواطن'] ?? '', 1000, 900, function($font) {
    //         $font->file(public_path('fonts/arial.ttf'));
    //         $font->size(36);
    //         $font->color('#000');
    //         $font->align('right');
    //         $font->valign('top');
    //     });

    //     // إدراج صور إضافية
    //     for ($i = 1; $i <= 4; $i++) {
    //         $key = "صورة $i";
    //         if (!empty($data[$key])) {
    //             try {
    //                 $smallImg = Image::make($data[$key])->resize(400, 300);
    //                 $x = 1000 + ($i - 1) * 420;
    //                 $y = 1200;
    //                 $img->insert($smallImg, 'top-left', $x, $y);
    //             } catch (\Exception $e) {
    //                 // تجاهل الصورة إذا كانت غير صالحة
    //             }
    //         }
    //     }

    //     $filename = 'certificate_'.time().'.png';
    //     $img->save(public_path('certificates/' . $filename));

    //     return response()->json([
    //         'message' => 'تم توليد الشهادة بنجاح',
    //         'image_url' => asset('certificates/' . $filename),
    //     ]);
    // }

    // ✅ توليد صورة باستخدام HTML + CSS عبر Browsershot
    public function generateImageFromHTML(Request $request)
    {
        $data = $request->input('data');

        $html = view('placemarks.certificate_image', compact('data'))->render();

        $filename = 'certificate_' . time() . '.png';
        $path = public_path('certificates/' . $filename);

        Browsershot::html($html)
            ->format('A3')
            ->margins(0, 0, 0, 0)
            ->windowSize(2480, 3508)
            ->save($path);

        // إعادة توجيه المستخدم لعرض الصورة
        return redirect()->route('placemarks.showCertificateImage', ['filename' => $filename]);
    }



    
    // ✅ صفحة عرض صورة الشهادة
    public function showCertificateImage($filename)
    {
        $imageUrl = asset('certificates/' . $filename);
        return view('placemarks.show_certificate_image', compact('imageUrl'));
    }
}
