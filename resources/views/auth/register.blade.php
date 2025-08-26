@extends('layout')

@section('content')
<div class="container mt-5" style="max-width:400px">
    <h2 class="mb-4 text-center">تسجيل حساب جديد</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label>الاسم</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success w-100">تسجيل</button>
    </form>

    <p class="mt-3 text-center">لديك حساب؟ <a href="{{ route('login') }}">تسجيل الدخول</a></p>
</div>
@endsection
