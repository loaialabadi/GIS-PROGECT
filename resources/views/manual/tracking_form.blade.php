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
            <input type="text" id="transaction_number" name="transaction_number" class="form-control" value="{{ old('transaction_number') }}" required>
            @error('transaction_number')
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
            <label for="center_name" class="form-label">اسم المركز</label>
            <select id="center_name" name="center_name" class="form-control">
                <option value="">-- اختر المركز --</option>
                <option value="مركز قنا" {{ old('center_name') == 'مركز قنا' ? 'selected' : '' }}>مركز قنا</option>
                <option value="مركز دشنا" {{ old('center_name') == 'مركز دشنا' ? 'selected' : '' }}>مركز دشنا</option>
                <option value="مركز نجع حمادي" {{ old('center_name') == 'مركز نجع حمادي' ? 'selected' : '' }}>مركز نجع حمادي</option>
                <option value="مركز قوص" {{ old('center_name') == 'مركز قوص' ? 'selected' : '' }}>مركز قوص</option>
                <option value="مركز نقادة" {{ old('center_name') == 'مركز نقادة' ? 'selected' : '' }}>مركز نقادة</option>
            </select>
            @error('center_name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="area" class="form-label">المنطقة</label>
            <input type="text" id="area" name="area" class="form-control" value="{{ old('area') }}">
        </div>

 

        <div class="mb-3">
            <label for="notes" class="form-label">ملاحظات</label>
            <textarea id="notes" name="notes" class="form-control">{{ old('notes') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="inspector_name" class="form-label">اسم القائم بالمتابعة</label>
            <select id="inspector_name" name="inspector_name" class="form-control">
                <option value="">اختر الاسم</option>
                <option value="سيد عبيد" {{ old('inspector_name') == 'سيد عبيد' ? 'selected' : '' }}>سيد عبيد</option>
                <option value="الحسيني سعيد" {{ old('inspector_name') == 'الحسيني سعيد' ? 'selected' : '' }}>الحسيني سعيد</option>
                <option value="احمد عبدالرحمن" {{ old('inspector_name') == 'احمد عبدالرحمن' ? 'selected' : '' }}>احمد عبدالرحمن</option>
                <option value="محمد عبدالخالق" {{ old('inspector_name') == 'محمد عبدالخالق' ? 'selected' : '' }}>محمد عبدالخالق</option>
                <option value="مصطفي مهران" {{ old('inspector_name') == 'مصطفي مهران' ? 'selected' : '' }}>مصطفي مهران</option>
                <option value="محمد عبدالحميد" {{ old('inspector_name') == 'محمد عبدالحميد' ? 'selected' : '' }}>محمد عبدالحميد</option>
            </select>
        </div>

        <!-- حقول حالة التتبع 5 -->
        <h5>حالة التتبع</h5>
        @for ($i = 0; $i < 5; $i++)
            <div class="mb-3">
                <label for="tracking_status_{{ $i }}" class="form-label">الوصف {{ $i + 1 }}</label>
                <input type="text" id="tracking_status_{{ $i }}" name="tracking_status[]" class="form-control" value="{{ old('tracking_status.' . $i) }}">
            </div>
        @endfor

        <!-- بيانات GIS -->
        <h5>بيانات GIS</h5>

        <div class="mb-3">
            <label for="gis_name" class="form-label">اسم مسؤول GIS</label>
            <input type="text" id="gis_name" name="gis_name" class="form-control" value="{{ old('gis_name') }}">
        </div>

  

        <!-- زر جديد للمعاينة -->
        <button type="submit" name="action" value="preview" class="btn btn-secondary">👁️ معاينة الشهادة</button>
        <button type="submit" name="action" value="save" class="btn btn-primary">💾 حفظ الشهادة</button>

    </form>
</div>
@endsection
