@extends('layout')

@section('content')
<div class="container mt-5" style="max-width:400px">
    <h2 class="mb-4 text-center">تسجيل الدخول</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">تسجيل الدخول</button>
    </form>

    <p class="mt-3 text-center">ليس لديك حساب؟ <a href="{{ route('register') }}">سجل هنا</a></p>
</div>
@endsection
