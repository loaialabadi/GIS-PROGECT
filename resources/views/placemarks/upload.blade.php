<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>📤 رفع ملف Excel</title>
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            background-color: #f4f4f4;
            padding: 40px;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 500px;
            margin: auto;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="file"],
        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

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

        <label for="certificate_name">📝 اسم الشهادة:</label>
        <input type="text" name="certificate_name" id="certificate_name" placeholder="مثال: شهادة إتمام الدورة">

        <label for="certificate_date">📅 التاريخ:</label>
        <input type="date" name="certificate_date" id="certificate_date">

        <label for="file_type">📂 نوع البيانات:</label>
        <select name="file_type" id="file_type">
            <option value="students">بيانات طلاب</option>
            <option value="teachers">بيانات معلمين</option>
            <option value="results">نتائج</option>
        </select>

        <button type="submit">👁️ عرض البيانات</button>
    </form>
</div>

</body>
</html>
