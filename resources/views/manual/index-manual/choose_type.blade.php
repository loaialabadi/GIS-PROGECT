@extends('layout')
@section('content')
<div class="card p-4">
    <h3 class="mb-4 text-center">🔘 اختر نوع الشهادة</h3>

    <div class="d-grid gap-3">
        <a href="{{ route('manual.survey') }}" class="btn btn-outline-primary btn-lg">
            📐 رفع مساحي
        </a>

        <a href="{{ route('manual.utilities') }}" class="btn btn-outline-warning btn-lg">
            💡 كشف مرافق
        </a>

        <a href="{{ route('manual.tracking') }}" class="btn btn-outline-success btn-lg">
            📸 تتبع زمني
        </a>
    </div>
</div>
@endsection
