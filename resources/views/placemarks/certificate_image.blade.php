<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body {
          font-family: 'Arial', sans-serif;
          margin: 0;
          padding: 0;
          background: #fff;
        }

        .container {
          border: 2px solid black;
          max-width: 1200px;
          margin: auto;
          padding: 5px;
          box-sizing: border-box;
        }

        .header {
          background: #fde5a6;
          text-align: center;
          font-size: 24px;
          font-weight: bold;
          padding: 10px 0;
          border: 1px solid black;
        }

        .main-content {
          display: flex;
          margin-top: 10px;
        }

        .boxes {
          flex: 2;
          padding: 5px;
        }

        .right-section {
          flex: 1;
          padding: 5px;
          border-right: 1px solid black;
        }

        .logos {
          display: flex;
          justify-content: space-between;
          margin-bottom: 5px;
        }

        .logo {
          width: 60px;
          height: 60px;
          border: 1px solid black;
        }

        .section.title {
          font-weight: bold;
          margin-top: 10px;
          margin-bottom: 5px;
        }

        .info-table, .status-table {
          width: 100%;
          border-collapse: collapse;
          margin-bottom: 10px;
        }

        .info-table td, .status-table td {
          border: 1px solid black;
          padding: 4px;
          font-size: 14px;
        }

        .footer {
          margin-top: 10px;
        }

        .service {
          display: flex;
          justify-content: space-between;
          margin: 3px 0;
        }

        .director {
          margin-top: 10px;
        }

        .stamp {
          border: 1px solid black;
          border-radius: 50%;
          width: 50px;
          height: 50px;
          text-align: center;
          line-height: 50px;
          margin-top: 10px;
        }

        .bottom-note {
          font-size: 12px;
          text-align: center;
          border-top: 1px solid black;
          padding: 5px;
          margin-top: 10px;
        }

        .drop-container {
            position: relative;
            display: inline-block;
            width: 100%;
            max-width: 100%;
        }

        #certificate {
            width: 100%;
            height: auto;
            display: block;
        }

        #drop-area {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            border: 2px dashed #aaa;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            color: #666;
            font-weight: bold;
        }

        #overlay-image {
            position: absolute;
            top: 50px;
            left: 50px;
            max-width: 200px;
            z-index: 20;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">📄 شهادة تتبع زمني لمنشأ</div>

        <div class="main-content">
            <div class="right-section">
                <div class="logos">
                    <div class="logo"></div>
                    <div class="logo"></div>
                </div>

                <div class="section title">بيانات مقدم الطلب</div>
                <table class="info-table">
                    <tr>
                        <td>اسم العميل</td>
                        <td>{{ $data['الاسم'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>الرقم القومي</td>
                        <td>{{ $data['رقم بطاقة المواطن'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>عنوان الموقع</td>
                        <td>{{ $data['عنوان القطعة'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>الفرع من الشبكة</td>
                        <td>{{ $data['جهة التوريد'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>المنطقة</td>
                        <td>{{ $data['المنطقة'] ?? '' }}</td>
                    </tr>
                </table>

                <div class="section title">موقف التتبع</div>
                <table class="status-table">
                    <tr>
                        <td>التاريخ</td>
                        <td>الموقف</td>
                    </tr>
                    <tr>
                        <td>10-10-2010</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>10.20.2018</td>
                        <td>{{ $data['المنطقة'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>10-15-2025</td>
                        <td>{{ $data['القطاع'] ?? '' }}</td>
                    </tr>
                </table>
            </div>

            <div class="boxes">
                <div class="drop-container">
                    <img id="certificate" src="/certificates/{{ $filename }}" alt="شهادة">
                    <div id="drop-area" ondragover="event.preventDefault()" ondrop="handleDrop(event)">
                        🖼️ اسحب صورة هنا
                    </div>
                    <img id="overlay-image" alt="Overlay">
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="service">
                <div>خدمة عملاء <br> توقيع:</div>
                <div>رفع مساحي <br> توقيع:</div>
                <div>GIS إصدار <br> توقيع:</div>
            </div>
            <div class="director">
                يعتمد رئيس المركز<br>
                التوقيع:<br>
                م/ محمد مصطفى ياسين
            </div>
            <div class="stamp">ختم</div>

            <div class="bottom-note">
                تم الإرشاد عن الموقع بمعرفة مقدم الطلب وذلك دون أدنى مسئولية على مركز معلومات شبكات المرافق بقنا - ولا يعد هذا البيان مستند ملكية - ويستخدم هذا البيان لأغراض الحصر دون إنابة
            </div>
        </div>
    </div>

    <script>
    function handleDrop(event) {
        event.preventDefault();
        const file = event.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const overlay = document.getElementById('overlay-image');
                overlay.src = e.target.result;
                overlay.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }
    </script>
</body>
</html>
