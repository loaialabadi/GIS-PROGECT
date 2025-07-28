
@extends('layout')
@section('content')

<form method="POST" action="{{ route('certificates.update', ['type' => $type, 'id' => $certificate->id]) }}">
    @csrf

    <label>اسم العميل:</label>
    <input type="text" name="client_name" value="{{ $certificate->client_name }}">

    <label>رقم المعاملة:</label>
    <input type="text" name="transaction_number" value="{{ $certificate->transaction_number }}">
    <label>الرقم القومي:</label>
    <input type="text" name="national_id" value="{{ $certificate->national_id }}">
    <label>وصف المبنى:</label>
    <input type="text" name="building_description" value="{{ $certificate->building_description }}">
    <label>المركز:</label>  
    <input type="text" name="center_name" value="{{ $certificate->center_name }}">
    <label >القائم باعمال gis</label>
    <input type="text" name="gis_name" value="{{ $certificate->gis_name }}">
    <label>القائم بالمعاينه:</label>
    <input type="text" name="inspector_name" value="{{ $certificate->inspector_name }}">


    {{-- باقي الحقول --}}

<label>حالة التتبع:</label>
<div id="tracking-status-wrapper">
    @php
        $trackingStatus = json_decode($certificate->tracking_status, true) ?? [];
    @endphp

    @foreach($trackingStatus as $key => $value)
        <div style="margin-bottom: 10px;">
            <label>{{ $key }}:</label>
            <input type="text" name="tracking_status[{{ $key }}]" value="{{ $value }}">
        </div>
    @endforeach

    {{-- لإضافة مفتاح جديد --}}
    <div>
        <label>إضافة مفتاح جديد:</label>
        <input type="text" name="tracking_status[new_key]" placeholder="اسم المفتاح الجديد">
        <input type="text" name="tracking_status[new_value]" placeholder="القيمة">
    </div>
</div>


    <button type="submit">حفظ</button>
</form>
@endsection