@extends('layout')
@section('content')
<div class="container mt-2">
    <h2>📝 إنشاء شهادة جديدة من شهادة موجودة</h2>

    <form method="POST" action="{{ route('tracking_certificates.storeFromExisting') }}" enctype="multipart/form-data">
        @csrf

        {{-- بيانات العميل --}}
        <div class="mb-3">
            <label for="client_name" class="form-label">اسم العميل *</label>
            <input type="text" id="client_name" name="client_name" class="form-control"
                value="{{ old('client_name', $data->client_name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="national_id" class="form-label">رقم البطاقة *</label>
            <input type="text" id="national_id" name="national_id" class="form-control"
                value="{{ old('national_id', $data->national_id ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="transaction_number" class="form-label">رقم المعاملة *</label>
            <input type="text" id="transaction_number" name="transaction_number" class="form-control"
                value="{{ old('transaction_number', $data->transaction_number ?? '') }}" required>
        </div>

        {{-- الغرض والإحداثيات --}}
        <div class="mb-3">
            <label for="purpose" class="form-label">الغرض من الشهادة *</label>
            <input type="text" id="purpose" name="purpose" class="form-control"
                value="{{ old('purpose', $data->purpose ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="coordinates" class="form-label">الإحداثيات</label>
            <input type="text" id="coordinates" name="coordinates" class="form-control"
                value="{{ old('coordinates', $data->coordinates ?? '') }}">
        </div>

        <div class="mb-3">
            <label for="building_description" class="form-label">وصف المبنى</label>
            <textarea id="building_description" name="building_description" class="form-control">{{ old('building_description', $data->building_description ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="center_name" class="form-label">اسم المركز</label>
            <input type="text" id="center_name" name="center_name" class="form-control"
                value="{{ old('center_name', $data->center_name ?? '') }}">
        </div>

        {{-- التواريخ --}}
        <h5>وصف المتابعة لكل تاريخ موجود:</h5>
        @if(!empty($trackingStatus))
            @foreach($trackingStatus as $date => $status)
                <div class="mb-2 border p-2 rounded">
                    <label class="form-label"><strong>{{ $date }}</strong></label>
                    <input type="text" class="form-control" name="tracking_status[{{ $date }}]"
                        value="{{ old('tracking_status.'.$date, $status) }}">
                </div>
            @endforeach
        @else
            <p>لا توجد بيانات تتبع.</p>
        @endif

        {{-- رفع صورة جديدة --}}
        <div class="mb-3">
            <label for="certificate_file" class="form-label">رفع صورة الشهادة (اختياري)</label>
            <input type="file" id="certificate_file" name="certificate_file" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary mt-3">💾 حفظ الشهادة الجديدة</button>
    </form>
</div>
@endsection
