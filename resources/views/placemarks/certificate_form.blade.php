@extends('layout')
@section('content')

<h2>📝 تعديل بيانات الشهادة</h2>

{{-- ✅ نموذج حفظ البيانات --}}
<form method="POST" action="{{ route('placemarks.saveData') }}" enctype="multipart/form-data">
    @csrf

    {{-- الحقول النصية --}}
    @foreach($placemark as $key => $value)
        <div style="margin-bottom: 10px;">
            <label><strong>{{ $key }}</strong></label><br>
            <input type="text" name="data[{{ $key }}]" value="{{ $value }}" style="width: 100%;">
        </div>
    @endforeach



    <button type="submit">💾 حفظ البيانات في قاعدة البيانات</button>
</form>


<br>

{{-- ✅ نموذج توليد الشهادة --}}
<form method="POST" action="{{ route('certificate.generate.image') }}" id="generateImageForm" target="_blank">
    @csrf
    @foreach($placemark as $key => $value)
        <input type="hidden" name="data[{{ $key }}]" value="{{ $value }}">
    @endforeach

    <button type="submit">📄 توليد الشهادة كصورة</button>
</form>


@endsection
