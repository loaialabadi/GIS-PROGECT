<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8" />
<style>
    @page { margin: 0; }
    body {
        margin: 0;
        padding: 0;
        width: 420mm;  /* A3 Landscape */
        height: 297mm;
        background: url("{{ public_path('images/certificate_bg.png') }}") no-repeat;
        background-size: cover;
        position: relative;
        font-family: Arial, sans-serif;
        direction: rtl;
    }
    .field {
        position: absolute;
        font-size: 18px;
        font-weight: bold;
        color: #000;
    }
    .name { top: 150px; right: 250px; width: 400px; }
    .id { top: 190px; right: 250px; width: 400px; }
    .area { top: 230px; right: 250px; width: 400px; }
    /* صور */
    .images {
        position: absolute;
        bottom: 50px;
        right: 50px;
        display: flex;
        gap: 10px;
    }
    .images img {
        width: 150px;
        height: 100px;
        object-fit: cover;
        border: 1px solid #ccc;
    }
</style>
</head>
<body>
    <div class="field name">{{ $placemark['الاسم'] ?? '' }}</div>
    <div class="field id">{{ $placemark['رقم بطاقة المواطن'] ?? '' }}</div>
    <div class="field area">{{ $placemark['مساحة القطعة بالمتر المربع'] ?? '' }}</div>

    <div class="images">
        @for ($i = 1; $i <= 4; $i++)
            @php $imgKey = "صورة $i"; $imgUrl = $placemark[$imgKey] ?? null; @endphp
            @if($imgUrl)
                <img src="{{ $imgUrl }}" alt="صورة {{ $i }}">
            @endif
        @endfor
    </div>
</body>
</html>
