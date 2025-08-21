<?php

namespace App\Http\Controllers;

use App\Models\TrackingCertificate;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // عرض صفحة تحتوي على جميع شهادات التتبع مرتبة بآخر تاريخ
    public function index()
    {
        $certificates = TrackingCertificate::latest()->get();
        return view('transactions.index', compact('certificates'));
    }

    // البحث عن شهادات حسب رقم المعاملة المدخل
    public function search(Request $request)
    {
        // التحقق من صحة رقم المعاملة
        $request->validate([
            'transaction_number' => 'required|string',
        ]);

        // جلب الشهادات التي تطابق رقم المعاملة
        $certificates = TrackingCertificate::where('transaction_number', $request->transaction_number)->get();

        // إذا لم توجد شهادات، إعادة التوجيه مع رسالة خطأ
        if ($certificates->isEmpty()) {
            return redirect()->route('transactions.index')->with('error', 'لم يتم العثور على شهادات برقم المعاملة هذا.');
        }

        // عرض الشهادات التي تم العثور عليها
        return view('transactions.index', compact('certificates'));
    }



    // // تحديث حالة التسليم لشهادة معينة بناءً على المرحلة (1, 2, أو 3)
    // public function deliver(Request $request, $id)
    // {
    //     // البحث عن الشهادة المطلوبة أو إظهار خطأ 404
    //     $certificate = TrackingCertificate::findOrFail($id);

    //     // التحقق من صحة الحالة الجديدة (يجب أن تكون 1 أو 2 أو 3)
    //     $request->validate([
    //         'status' => 'required|in:1,2,3',
    //     ]);

    //     // تحديث حالة التسليم بالرقم المرسل
    //     $certificate->delivery_status = $request->status;

    //     // إذا وصلت الحالة للمرحلة 3 (التسليم النهائي)، يتم حفظ تاريخ التسليم
    //     if ($request->status == 3) {
    //         $certificate->delivered_at = now();
    //     }

    //     // حفظ التغييرات
    //     $certificate->save();

    //     // إعادة التوجيه مع رسالة نجاح
    //     return redirect()->back()->with('success', 'تم تحديث حالة التسليم بنجاح.');
    // }
}
