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
<div class="sidebar vh-100 shadow-lg" id="sidebar" style="width: 250px;">
    <div class="text-center py-4 border-bottom">
        <h4 class="fw-bold"> القائمة</h4>
    </div>

<div class="list-group list-group-flush">
    <a href="{{ route('placemarks.upload') }}" class="list-group-item">
        ادخال اكسيل
    </a>

    <a href="{{ route('manual.choose') }}" class="list-group-item">
        ادخال يدوي
    </a>

    <a href="{{ route('certificates.search.form') }}" class="list-group-item">
        بحث
    </a>

    <a href="{{ route('tracking_certificates.review', ['status' => 'pending']) }}" class="list-group-item">
        مراجعة الشهادات
    </a>

    <a href="{{ route('tracking_certificates.delivery', ['status' => 'delivered']) }}" class="list-group-item">
        خدمة العملاء للتسليم
    </a>

    <a href="{{ route('tracking_certificates.stifaa') }}" class="list-group-item">
        استيفاء الشهادات
    </a>

    <a href="{{ route('transactions.index') }}" class="list-group-item">
        الشهادات
    </a>

    <a href="{{ route('reports.index') }}" class="list-group-item">
        تقارير الشهادات
    </a>

    <a href="{{ route('employees.index') }}" class="list-group-item">
        إدارة الموظفين
    </a>

    <a href="{{ route('admin.users.index') }}" class="list-group-item">
        إدارة المستخدمين
    </a>
</div>

    <div class="mt-auto p-3 border-top">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center">
                <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
            </button>
        </form>
    </div>
</div>


  {{-- 🔹 Main Content --}}
  <div class="content container py-4">
      @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
      @endif


      @yield('content')



  {{-- JS --}}
  <script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const content = document.querySelector('.content');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
      content.style.marginRight = sidebar.classList.contains('collapsed') ? '70px' : '250px';
    });


    <script>
    document.querySelectorAll('.table tbody tr').forEach(row => {
        row.addEventListener('click', () => {
            // إزالة التحديد من الصفوف الأخرى
            document.querySelectorAll('.table tbody tr').forEach(r => r.classList.remove('selected'));
            // تفعيل التحديد على الصف الحالي
            row.classList.add('selected');
        });
    });
</script>

  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
