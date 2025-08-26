@extends('layout')

@section('content')
<h2>تعديل مستخدم</h2>

<form action="{{ route('admin.users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>الاسم</label>
        <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>البريد الإلكتروني</label>
        <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>كلمة المرور الجديدة (اتركها فارغة إذا لا تريد تغييرها)</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="mb-3">
        <label>تأكيد كلمة المرور</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>
    <div class="mb-3">
        <label>الدور</label>
        <select name="role" class="form-control" required>
            <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
            <option value="reviewer" {{ $user->role=='reviewer'?'selected':'' }}>مراجع</option>
            <option value="delivery" {{ $user->role=='delivery'?'selected':'' }}>تسليم</option>
            <option value="user" {{ $user->role=='user'?'selected':'' }}>مستخدم عادي</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">تحديث</button>
</form>
@endsection
