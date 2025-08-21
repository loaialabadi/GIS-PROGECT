@extends('layout')
@section('content')
<div class="container mt-2">
    <h2>📝 تعديل بيانات شهادة تتبع</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- زر عرض الشهادة --}}
    @if(!empty($certificate->certificate_path) && Storage::exists('public/' . $certificate->certificate_path))
        <div class="mb-3">
            <a href="{{ Storage::url($certificate->certificate_path) }}" target="_blank" class="btn btn-info">
                📄 عرض الشهادة
            </a>
        </div>
    @endif

    <form method="POST" action="{{ route('tracking_certificates.update', $certificate->id) }}">
        @csrf
        @method('PUT')

        {{-- بيانات العميل --}}
        <div class="mb-3">
            <label for="client_name" class="form-label">اسم العميل *</label>
            <input type="text" id="client_name" name="client_name" class="form-control" 
                value="{{ old('client_name', $certificate->client_name) }}" required>
            @error('client_name')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label for="national_id" class="form-label">رقم البطاقة *</label>
            <input type="text" id="national_id" name="national_id" class="form-control" 
                value="{{ old('national_id', $certificate->national_id) }}" required>
            @error('national_id')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label for="transaction_number" class="form-label">رقم المعاملة *</label>
            <input type="text" id="transaction_number" name="transaction_number" class="form-control" 
                value="{{ old('transaction_number', $certificate->transaction_number) }}" required>
            @error('transaction_number')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        {{-- الغرض والإحداثيات --}}
        <div class="mb-3">
            <label for="purpose" class="form-label">الغرض من الشهادة *</label>
            <input type="text" id="purpose" name="purpose" class="form-control" 
                value="{{ old('purpose', $certificate->purpose) }}" required>
            @error('purpose')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label for="coordinates" class="form-label">الإحداثيات</label>
            <input type="text" id="coordinates" name="coordinates" class="form-control" 
                value="{{ old('coordinates', $certificate->coordinates) }}">
            @error('coordinates')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        {{-- وصف المبنى والمركز --}}
        <div class="mb-3">
            <label for="building_description" class="form-label">وصف المبنى</label>
            <textarea id="building_description" name="building_description" class="form-control">{{ old('building_description', $certificate->building_description) }}</textarea>
            @error('building_description')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label for="center_name" class="form-label">اسم المركز</label>
            <select id="center_name" name="center_name" class="form-control">
                <option value="">-- اختر المركز --</option>
                @foreach(['مركز قنا','مركز دشنا','مركز نجع حمادي','مركز قوص','مركز نقادة'] as $center)
                    <option value="{{ $center }}" {{ old('center_name', $certificate->center_name) == $center ? 'selected' : '' }}>{{ $center }}</option>
                @endforeach
            </select>
            @error('center_name')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label for="area" class="form-label">المنطقة</label>
            <input type="text" id="area" name="area" class="form-control" value="{{ old('area', $certificate->area) }}">
        </div>

        {{-- الملاحظات واسم المفتش --}}
        <div class="mb-3">
            <label for="notes" class="form-label">ملاحظات</label>
            <textarea id="notes" name="notes" class="form-control">{{ old('notes', $certificate->notes) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="inspector_name" class="form-label">اسم القائم بالمتابعة</label>
            <select id="inspector_name" name="inspector_name" class="form-control">
                <option value="">اختر الاسم</option>
                @foreach(['سيد عبيد','الحسيني سعيد','احمد عبدالرحمن','محمد عبدالخالق','مصطفي مهران','محمد عبدالحميد'] as $inspector)
                    <option value="{{ $inspector }}" {{ old('inspector_name', $certificate->inspector_name) == $inspector ? 'selected' : '' }}>{{ $inspector }}</option>
                @endforeach
            </select>
        </div>

        {{-- تعديل التواريخ الموجودة فقط --}}
        <h5>تعديل وصف المتابعة لكل تاريخ موجود:</h5>
@php
    $trackingStatusRaw = old('tracking_status', $certificate->tracking_status ?? []);
    $trackingStatus = is_string($trackingStatusRaw) ? json_decode($trackingStatusRaw, true) : $trackingStatusRaw;
@endphp
@if(is_array($trackingStatus) && count($trackingStatus) > 0)
    @foreach($trackingStatus as $date => $status)
        <div class="mb-2 border p-2 rounded">
            <label class="form-label"><strong>{{ $date }}</strong></label>
            <input type="text" class="form-control" name="tracking_status[{{ $date }}]" value="{{ $status }}" placeholder="أدخل وصف المتابعة">
        </div>
    @endforeach
@else
    <p>لا توجد بيانات تتبع لتعديلها.</p>
@endif

        {{-- بيانات GIS --}}
        <h5>بيانات GIS</h5>
        <div class="mb-3">
            <label for="gis_name" class="form-label">اسم مسؤول GIS</label>
            <input type="text" id="gis_name" name="gis_name" class="form-control" value="{{ old('gis_name', $certificate->gis_name) }}">
        </div>
<a href="{{ route('tracking_certificates.create_from_existing', $certificate->id) }}" 
   class="btn btn-primary btn-sm">
    📄 إنشاء نسخة جديدة
</a>

        <button type="submit" class="btn btn-primary">💾 حفظ التعديلات</button>
    </form>
</div>
@endsection
