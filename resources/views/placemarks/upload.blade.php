@extends('layout')

@section('content')


<div class="container">
    <h2>📤 رفع ملف Excel مع خيارات إضافية</h2>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('placemarks.import') }}" enctype="multipart/form-data">
        @csrf

        <label for="excel_file">📁 اختر ملف Excel:</label>
        <input type="file" name="excel_file" id="excel_file" required accept=".xlsx,.xls">


        <button type="submit">👁️ عرض البيانات</button>
    </form>
</div>



<div class="container">
    <h2>عرض الشهايد</h2>


@endsection