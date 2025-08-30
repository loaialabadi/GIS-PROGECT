<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول - مركز معلومات شبكات المرافق</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0d6efd, #198754);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: "Tajawal", sans-serif;
        }
        .login-card {
            max-width: 420px;
            width: 100%;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #0d6efd, #198754);
            padding: 30px 20px;
            text-align: center;
            color: #fff;
        }
        .login-header h3 {
            margin: 10px 0 0;
            font-weight: bold;
        }
        .logo {
            width: 80px;
            height: 80px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
            font-weight: bold;
            font-size: 22px;
            color: #0d6efd;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .btn-custom {
            background: linear-gradient(135deg, #0d6efd, #198754);
            border: none;
        }
        .btn-custom:hover {
            background: linear-gradient(135deg, #198754, #0d6efd);
        }
        small.credit {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #666;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="login-card">
     
    <div class="login-header">
        <div class="logo">
            <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="img-fluid" style="max-width:70px;">
        </div>
        <h3>مركز معلومات شبكات المرافق</h3>
    </div>

    <!-- نموذج تسجيل الدخول -->
    <div class="p-4">
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

            <button type="submit" class="btn btn-custom text-white w-100">تسجيل الدخول</button>
        </form>

        <small class="credit">إعداد: م. لؤي حمدون</small>
    </div>
</div>

</body>
</html>
