<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width:400px">
    <div class="card shadow p-4 rounded">
        <h2 class="mb-4 text-center">تسجيل الدخول</h2>

        {{-- عرض رسائل --}}
        @if(session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                @error('email') 
                    <small class="text-danger">{{ $message }}</small> 
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">كلمة المرور</label>
                <input type="password" name="password" class="form-control" required>
                @error('password') 
                    <small class="text-danger">{{ $message }}</small> 
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">تسجيل الدخول</button>
        </form>

        <p class="mt-3 text-center">
            ليس لديك حساب؟ 
            <a href="{{ route('register') }}">سجل هنا</a>
        </p>
    </div>
</div>

</body>
</html>
