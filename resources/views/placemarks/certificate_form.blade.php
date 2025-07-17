<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>📝 تعديل بيانات الشهادة</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            padding: 30px;
            direction: rtl;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #005dbaff;
        }
        form {
            max-width: 700px;
            margin: 0 auto;
            background-color: #fff;
            padding: 25px 30px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        div.form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 16px;
            color: #34495e;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px 12px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus {
            border-color: #007bff;
            outline: none;
        }
        button {
            background-color: #007bff;
            color: white;
            font-size: 17px;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        @media(max-width: 768px) {
            form {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>

    <h2>📝 تعديل بيانات الشهادة</h2>
<form method="POST" action="{{ route('certificate.generate.image') }}">
    @csrf
    @foreach($placemark as $key => $value)
        <div style="margin-bottom: 10px;">
            <label><strong>{{ $key }}</strong></label><br>
            <input type="text" name="data[{{ $key }}]" value="{{ $value }}" style="width: 100%;">
        </div>
    @endforeach
    <button type="submit">📄 توليد الشهادة كصورة</button>
</form>


</body>
</html>
