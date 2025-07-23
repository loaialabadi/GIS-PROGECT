@extends('layout')
@section('content')
<div class="container mt-5">
    <h2>📝 إدخال بيانات شهادة تتبع</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('tracking_certificates.store') }}">
        @csrf

        <div class="mb-3">
            <label for="client_name" class="form-label">اسم العميل *</label>
            <input type="text" id="client_name" name="client_name" class="form-control" value="{{ old('client_name') }}" required>
            @error('client_name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="national_id" class="form-label">رقم البطاقة *</label>
            <input type="text" id="national_id" name="national_id" class="form-control" value="{{ old('national_id') }}" required>
            @error('national_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="transaction_number" class="form-label">رقم المعاملة *</label>
            <input type="text" id="transaction_id" name="transaction_id" class="form-control" value="{{ old('transaction_number') }}" required>
            @error('transaction_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="building_description" class="form-label">وصف المبنى</label>
            <textarea id="building_description" name="building_description" class="form-control">{{ old('building_description') }}</textarea>
            @error('building_description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="project_name" class="form-label">اسم المشروع</label>
            <input type="text" id="project_name" name="project_name" class="form-control" value="{{ old('project_name') }}">
        </div>

        <div class="mb-3">
            <label for="area" class="form-label">المنطقة</label>
            <input type="text" id="area" name="area" class="form-control" value="{{ old('area') }}">
        </div>

        <div class="mb-3">
            <label for="tracking_date" class="form-label">تاريخ المتابعة</label>
            <input type="date" id="tracking_date" name="tracking_date" class="form-control" value="{{ old('tracking_date') }}">
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">ملاحظات</label>
            <textarea id="notes" name="notes" class="form-control">{{ old('notes') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="inspector_name" class="form-label">اسم القائم بالمتابعة</label>
            <input type="text" id="inspector_name" name="inspector_name" class="form-control" value="{{ old('inspector_name') }}">
        </div>

        <button type="submit" class="btn btn-primary">💾 حفظ البيانات</button>
    </form>
</div>
@endsection
