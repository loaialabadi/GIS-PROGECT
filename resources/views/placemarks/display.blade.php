<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <title>📋 عرض بيانات Excel</title>
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            background-color: #f9f9f9;
            padding: 30px;
            direction: rtl;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        thead {
            background-color: #f0f0f0;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        tr:hover {
            background-color: #f1f7ff;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .no-data {
            text-align: center;
            color: red;
            font-size: 18px;
        }

        form {
            margin: 0;
        }
    </style>
</head>
<body>

    <h2>📋 عرض بيانات Excel</h2>

    @if(isset($headers) && isset($records) && count($records) > 0)
        <table>
            <thead>
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                    <th>🎓 شهادة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{{ $cell }}</td>
                        @endforeach
                        <td>
                            <form method="POST" action="{{ route('placemarks.certificate.preview') }}">
                                @csrf
                                @foreach($headers as $index => $key)
                                    <input type="hidden" name="data[{{ $key }}]" value="{{ $row[$index] ?? '' }}">
                                @endforeach
                                <button type="submit">عرض</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="no-data">❌ لا توجد بيانات لعرضها.</p>
    @endif

</body>
</html>
