<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        {!! file_get_contents(public_path('css/certificate.css')) !!}
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
                </table>

                <div class="section title">موقف التتبع</div>
                <table class="status-table">
                    <tr>
                        <td>التاريخ</td>
                        <td>الموقف</td>
                    </tr>
                    <tr>
                        <td>10-10-2010</td>
                        <td>لوووووووووووو</td>
                    </tr>
                    <tr>
                        <td>10.20.2018</td>
                        <td>{{ $data['المنطقة'] ?? '' }}</td>
                    </tr>
                    <tr>

                        <td>10-15-2025</td>
                        <td>{{ $data['القطاع'] ?? '' }}</td>

                </table>
            </div>

            <div class="boxes">
                @for ($i = 1; $i <= 4; $i++)
                    <div class="box">
                        <div class="box-title">صورة {{ $i }}</div>
                        @php $key = 'صورة ' . $i; @endphp
                        @if(!empty($data[$key]))
                            <img src="{{ public_path('uploads/' . $data[$key]) }}" style="max-width:100%; height:auto;">
                        @endif
                    </div>
                @endfor
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
</body>
</html>
