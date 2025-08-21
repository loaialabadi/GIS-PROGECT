@extends('layout')
@section('content')
<div class="card p-4">
    <h3 class="mb-4 text-center">💡 إدخال بيانات كشف مرافق</h3>

    <form method="POST" action="">
        @csrf

        <div class="mb-3">
            <label class="form-label">الاسم</label>
            <input type="text" name="الاسم" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">العنوان</label>
            <input type="text" name="عنوان القطعة" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">نوع المرفق</label>
            <input type="text" name="نوع المرفق" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">الجهة الطالبة</label>
            <input type="text" name="جهة التوريد" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">الحدود (بحري، قبلي، شرقي، غربي)</label>
            <input type="text" name="الحد البحرى" class="form-control mb-2" placeholder="الحد البحري">
            <input type="text" name="الحد القبلى" class="form-control mb-2" placeholder="الحد القبلي">
            <input type="text" name="الحد الشرقى" class="form-control mb-2" placeholder="الحد الشرقي">
            <input type="text" name="الحد الغربى" class="form-control" placeholder="الحد الغربي">
        </div>

        <button type="submit" class="btn btn-warning">📄 توليد الشهادة</button>
    </form>
</div>
@endsection
