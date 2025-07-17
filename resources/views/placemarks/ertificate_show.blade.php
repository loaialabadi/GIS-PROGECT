<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <title>عرض الشهادة</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            max-width: 10000px;
            margin: 20px auto;
            padding: 10px;
        }
        .certificate-img {
            width: 100%;
            max-width: 800px;
            border: 2px solid #333;
            margin-bottom: 20px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .data-table th, .data-table td {
            border: 1px solid #888;
            padding: 8px 12px;
            text-align: right;
        }
        .data-table th {
            background-color: #fde5a6;
        }
    </style>
</head>
<body>

    <h2>الشهادة</h2>

    <img src="{{ $imageUrl }}" alt="صورة الشهادة" class="certificate-img">

    <h3>بيانات الشهادة</h3>
    <table class="data-table">
        <tbody>
        @foreach($data as $key => $value)
            <tr>
                <th>{{ $key }}</th>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</body>
</html>
