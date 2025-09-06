@extends('layout')

@section('content')


@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<h2>إضافة مستخدم جديد</h2>

<form action="{{ route('admin.users.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>الاسم</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>البريد الإلكتروني</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>كلمة المرور</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>تأكيد كلمة المرور</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>الدور</label>
        <select name="role" class="form-control" required>
            <option value="admin">Admin</option>
            <option value="reviewer">مراجع gis</option>
            <option value="customer_service">خدمه عملاء</option>
            <option value="data_entry">مدخل بيانات</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">حفظ</button>
</form>
@endsection
