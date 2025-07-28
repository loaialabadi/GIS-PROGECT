@extends('layout')

@section('content')
    <div class="container">
        <h2>نتائج البحث</h2>

        @foreach($results as $certificate)
            <div class="card" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; border-radius: 10px;">
                <p><strong>رقم المعاملة:</strong> {{ $certificate->transaction_number }}</p>
                <p><strong>الاسم:</strong> {{ $certificate->client_name }}</p>
                <p><strong>الرقم القومي:</strong> {{ $certificate->national_id }}</p>
                <p><strong>وصف المبنى:</strong> {{ $certificate->building_description }}</p>
                <p><strong>المركز:</strong> {{ $certificate->center_name }}</p>
                <p><strong>المساحة:</strong> {{ $certificate->area }}</p>
                <p><strong>المفتش:</strong> {{ $certificate->inspector_name }}</p>
                <p><strong>GIS:</strong> {{ $certificate->gis_name }}</p>
                <p><strong>ملاحظات:</strong> {{ $certificate->notes }}</p>
                <p><strong>تاريخ الإنشاء:</strong> {{ $certificate->created_at }}</p>

                @php
                    $status = json_decode($certificate->tracking_status, true);
                @endphp

                @if($status)
                    <p><strong>حالة التتبع:</strong></p>
                    <ul>
                        @foreach($status as $key => $value)
                            <li>{{ $key }}: {{ $value }}</li>
                        @endforeach
                    </ul>
                @else
                    <p><strong>حالة التتبع:</strong> لا توجد بيانات</p>
                @endif

                <div style="margin-top: 10px;">
                    <a href="{{ route('certificates.edit', ['type' => strtolower(class_basename($certificate)), 'id' => $certificate->id]) }}" class="btn btn-primary">تعديل</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
