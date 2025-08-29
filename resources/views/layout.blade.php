<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'لوحة التحكم')</title>

  {{-- Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  {{-- FontAwesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  {{-- ملف CSS مخصص --}}
  <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
</head>
<body>


  {{-- 🔹 Topbar --}}
  <div class="topbar d-flex justify-content-between align-items-center p-2 shadow-sm">
      <div class="d-flex align-items-center gap-3">
          <div class="logo-item d-flex align-items-center gap-2">
              <img src="{{ asset('images/logo2.png') }}" alt="شعار المحافظة" style="height:40px;">
              <span>محافظة قنا</span>
          </div>
          <div class="logo-item d-flex align-items-center gap-2">
              <img src="{{ asset('images/logo1.png') }}" alt="مركز معلومات شبكات المرافق" style="height:40px;">
              <span>مركز معلومات شبكات المرافق</span>
          </div>
      </div>
      <div class="topbar-center fw-bold">م/ لؤي حمدون</div>
      @if(Auth::check())
          <div>مرحباً، {{ Auth::user()->name }}</div>
        @endif
      <div class="actions">
          <i id="toggleSidebar" class="fas fa-bars fs-4" title="تبديل القائمة"></i>
      </div>
  </div>

  {{-- 🔹 Sidebar --}}
  <div class="sidebar" id="sidebar">
      <h4 class="px-3 py-2">القائمة</h4>
      <a href="{{ route('placemarks.upload') }}"><i class="fas fa-home"></i> <span>ادخال اكسيل</span></a>
      <a href="{{ route('manual.choose') }}"><i class="fas fa-pen"></i> <span>ادخال يدوي</span></a>
      <a href="{{ route('certificates.search.form') }}"><i class="fas fa-list"></i> <span>بحث</span></a>
      <a href="{{ route('tracking_certificates.review', ['status' => 'pending']) }}"><i class="fas fa-eye"></i> <span>مراجعة الشهادات</span></a>
      <a href="{{ route('tracking_certificates.delivery', ['status' => 'delivered']) }}"><i class="fas fa-truck"></i> <span>خدمة العملاء للتسليم</span></a>
      <a href="{{ route('tracking_certificates.stifaa') }}"><i class="fas fa-check"></i> <span>استيفاء الشهادات</span></a>
      <a href="{{ route('transactions.index') }}"><i class="fas fa-file-alt"></i> <span>الشهادات</span></a>
      <a href="{{ route('admin.users.index') }}"><i class="fas fa-users-cog"></i> <span>إدارة المستخدمين</span></a>

      <form action="{{ route('logout') }}" method="POST" class="p-3">
          @csrf
          <button type="submit" class="btn btn-danger w-100">
              <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
          </button>
      </form>

      
  </div>

  {{-- 🔹 Main Content --}}
  <div class="content container py-4">
      @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <a href="{{ url()->previous() }}" class="btn btn-outline-primary mb-3">
          <i class="fas fa-arrow-left"></i> الرجوع
      </a>

      @yield('content')


          <div>اسم الراوت الحالي: 
        <strong>{{ Route::currentRouteName() ?? 'بدون اسم' }}</strong>
    </div>
    <div>الرابط الحالي: 
        <strong>{{ url()->current() }}</strong>
    </div>
  </div>

  {{-- JS --}}
  <script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const content = document.querySelector('.content');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
      content.style.marginRight = sidebar.classList.contains('collapsed') ? '70px' : '250px';
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
