@extends('layout')
@section('content')
<h3>📐 نموذج إدخال بيانات رفع مساحي</h3>
<form method="POST" action="#">
    @csrf
    <div class="mb-3">
        <label>الاسم</label>
        <input type="text" name="name" class="form-control" />
    </div>
    <div class="mb-3">
        <label>العنوان</label>
        <input type="text" name="address" class="form-control" />
    </div>
    <!-- أضف باقي الحقول المطلوبة -->
    <button type="submit" class="btn btn-success">💾 حفظ</button>
</form>
@endsection
