<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>شهادة تتبع زمني لمنشأ</title>
    <style>
        body { font-family: 'Arial', sans-serif; background: #eee; margin: 0; padding: 20px; }
        
  .container {
    display: flex;
    width: 1400px;
    margin: auto;
    background: #fff;
    border: 2px solid black;
    padding: 15px;
    box-sizing: border-box;
  }

        .right-column { flex: 1; border-left: 2px solid black; padding-left: 15px; display: flex; flex-direction: column; }
        .logos { display: flex; justify-content: space-between; gap: 40px; margin-bottom: 20px; }
        .logo-box { text-align: center; }
            .logo-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 14px;
}        .logo { width: 60px; height: 60px; object-fit: contain; border: 1px solid black; }
     table {
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 20px;
  }
  table, th, td {
    border: 1px solid black;
  }
  td {
    padding: 6px;
    font-size: 14px;
  }

        .left-columns { flex: 2; display: flex; flex-direction: column; padding-right: 15px; }
        .certificate-title { font-size: 24px; font-weight: bold; text-align: center; background: #fde5a6; padding: 12px; border: 1px solid black; margin-bottom: 35px; }
        .photos-container { display: grid; grid-template-columns: repeat(2, 1fr); grid-gap: 15px; margin-bottom: 15px; height: 800px; }
        .photo-box { height: 390px; border: 2px dashed #aaa; border-radius: 8px; background: #f9f9f9; display: flex; justify-content: center; align-items: center; color: #888; font-size: 14px; cursor: pointer; overflow: hidden; position: relative; }
        .photo-label { position: absolute; top: -25px; left: 50%; transform: translateX(-50%); background-color: rgba(0, 0, 0, 0.7); color: white; padding: 3px 10px; border-radius: 5px; font-weight: bold; white-space: nowrap; z-index: 10; pointer-events: none; }
        .photo-box img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .footer-text { max-width: 1200px; margin: 50px auto 0; font-size: 13px; text-align: center; border-top: 1px solid black; padding-top: 10px; color: #555; line-height: 1.3; }
        @media print {
            body { background: white; margin: 0; padding: 0; }
            #printBtn, #saveBtn { display: none !important; }
            @page { size: A3 landscape; margin: 1cm; }
            html, body { width: auto; height: auto; }
            .container { box-shadow: none !important; border: none !important; padding: 0 !important; margin: 0 auto !important; max-width: 100%; }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- العمود الأيمن -->
        <div class="right-column">
            <!-- اللوجوهات -->
        <div class="logos">
        <div class="logo-box">
                <img src="{{ asset('images/logo2.png') }}" class="logo" alt="Logo 2">

            <div class="logo-title">ديوان عام محافظة قنا</div>
        </div>

        <div class="logo-box">
                <img src="{{ asset('images/logo1.png') }}" class="logo" alt="Logo 1">

            <div class="logo-title">مركز معلومات شبكات المرافق بقنا</div>
        </div>
        </div>


            <!-- جدول بيانات العميل -->
            <table>
                <tr><td>اسم العميل</td><td>{{ $data->client_name }}</td></tr>
                <tr><td>الرقم القومي</td><td>{{ $data->national_id }}</td></tr>
                <tr><td>المركز</td><td>{{ $data->center_name }}</td></tr>
                <tr><td>رقم المعاملة</td><td>{{ $data->transaction_number }}</td></tr>
                <tr><td>الغرض</td><td>{{ $data->purpose }}</td></tr>
                <tr><td>الإحداثي</td><td>{{ $data->coordinates }}</td></tr>
            </table>

    <!-- جدول التتبع -->
@if(!empty($trackingStatus) && count($trackingStatus) > 0)
    <h5>حالة التتبع</h5>
    <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; border-collapse: collapse; table-layout: fixed;">
        <tr>
            <th style="width: 20%; text-align: center;">التاريخ</th>
            <th style="width: 80%; text-align: center;">الموقف</th>
        </tr>
        @foreach($trackingStatus as $date => $status)
            @if(!empty($status))
                <tr>
                    <td style="text-align: center; vertical-align: middle;">{{ $date }}</td>
                    <td style="vertical-align: middle; word-wrap: break-word; white-space: pre-wrap;">{{ $status }}</td>
                </tr>
            @endif
        @endforeach
    </table>
@endif

<h5 style="margin-bottom: 10px; font-size: 12px; color: red;">
    {{ $data['notes'] ?? 'لا توجد ملاحظات' }}
</h5>

<table border="1" cellspacing="0" cellpadding="10" style="border-collapse: collapse; width: 100%; margin-top: 20px; text-align: center; font-size: 14px;">
  <tr>
    <th>خدمة عملاء</th>
    <th>أعمال رفع ميداني</th>
    <th colspan="2">أعمال GIS</th>
  </tr>
  <tr>
    <td>
      <div><strong>الاسم:</strong> منى عبدالفتاح</div><br>
      <div style="border-top: 1px solid #000; margin-top: 10px;">
        <strong>التوقيع:.........</strong> {{ $data['customer_service_sign'] ?? '' }}
      </div>
    </td>
    <td>
      <div><strong>الاسم:</strong> {{ $data['inspector_name'] ?? '' }}</div><br>
      <div style="border-top: 1px solid #000; margin-top: 10px;">
        <strong>التوقيع:..........</strong> {{ $data['field_survey_sign'] ?? '' }}
      </div>
    </td>
    <!-- GIS - إعداد -->
    <td>
      <div><strong>الإعداد</strong></div><br>
      <div><strong>الاسم:</strong> {{ $data['gis_preparer_name'] ?? '' }}</div><br>
      <div style="border-top: 1px solid #000; margin-top: 10px;">
        <strong>التوقيع:.........</strong> 
      </div>
    </td>
    <!-- GIS - مراجعة -->
    <td>
      <div><strong>المراجعة</strong></div><br>
      <div><strong>الاسم:</strong> {{ $data['gis_reviewer_name'] ?? '' }}</div><br>
      <div style="border-top: 1px solid #000; margin-top: 10px;">
        <strong>التوقيع:.........</strong> 
      </div>
    </td>
  </tr>
</table>

<!-- جدول اعتماد رئيس المركز وختم الشعار -->
<table border="1" cellspacing="0" cellpadding="10" style="border-collapse: collapse; width: 100%; margin-top: 20px; text-align: center; font-size: 14px;">
  <tr>
    <th style="width: 50%;">يعتمد رئيس المركز</th>
    <th style="width: 50%;">ختم الشعار</th>
  </tr>
  <tr>
    <!-- بيانات رئيس المركز -->
    <td style="vertical-align: middle;">
      <div><strong>الاسم:</strong> {{ $data['manager_name'] ?? 'م. محمد مصطفى ياسين' }}</div>
      <div style="margin-top: 15px; border-top: 1px solid #000; display: inline-block; padding-top: 5px;">
        <strong>التوقيع:</strong> {{ $data['manager_sign'] ?? '.........' }}
      </div>
    </td>

    <!-- ختم الشعار -->
    <td style="height: 15px; vertical-align: middle;">
      <div style="width: 50px; height: 50px; border: 2px solid #000; border-radius: 50%; margin: 0 auto;"></div>
      <div style="margin-top: 10px;"> خاتم المركز</div>
    </td>
  </tr>
</table>
<!-- العمود الأيسر -->
    </div>
<div class="left-columns">
    <div class="certificate-title">شهادة تتبع زمني لمنشأ</div>

    <div class="photos-container">
        @foreach($trackingStatus as $date => $status)
            @if(!empty($date) && !empty($status))
                <div style="position: relative; margin-bottom: 15px;">
                    <!-- العنوان فوق الصورة -->
                    <div style="
                        position: absolute;
                        top: -25px;
                        left: 50%;
                        transform: translateX(-50%);
                        background-color: rgba(0, 0, 0, 0.7);
                        color: white;
                        padding: 3px 10px;
                        border-radius: 5px;
                        font-weight: bold;
                        white-space: nowrap;
                        z-index: 10;
                        pointer-events: none;
                    ">
                        صورة بتاريخ {{ $date }}
                    </div>

                    <!-- الصندوق الخاص بالصورة -->
                    <div class="photo-box" ondrop="drop(event, this)" ondragover="allowDrop(event)">
                        📷 اسحب الصورة هنا
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <div class="footer-text">
        تم الإرشاد عن الموقع بمعرفة مقدم الطلب وذلك دون أدنى مسؤولية على مركز معلومات شبكات المرافق بقنا - لا يعتد بهذا البيان كمستند ملكية - لا يستخدم هذا البيان إلا في الغرض المحرر من أجله.
    </div>
</div>
</div>





<button onclick="saveCertificate()" class="btn btn-success">
    🖼 حفظ الشهادة كصورة
</button>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function saveCertificate() {
    let container = document.querySelector('.container');
    let transactionNumber = "{{ $data['transaction_number'] ?? '0000' }}";

    html2canvas(container, { scale: 2 }).then(canvas => {
        canvas.toBlob(function(blob) {
            let formData = new FormData();
            formData.append("image", blob, "certificate.jpg"); // JPEG بدل PNG
            formData.append("transaction_number", transactionNumber);

            fetch("{{ route('tracking_certificates.save_temp_image') }}", {
                method: "POST",
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success'){
                    alert(data.message);
                    console.log("📂 رابط الصورة:", data.path);
                } else {
                    alert("❌ لم يتم الحفظ");
                }
            })
            .catch(err => alert("❌ خطأ أثناء الحفظ"));
        }, "image/jpeg", 0.85); // صيغة + جودة
    });
}

// السماح بالسحب
function allowDrop(ev) {
    ev.preventDefault();
}

// عند إسقاط صورة
function drop(ev, element) {
    ev.preventDefault();
    const file = ev.dataTransfer.files[0];

    if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let img = new Image();
            img.onload = function() {
                // نصغّر الصورة قبل عرضها
                let maxWidth = 800; // العرض الأقصى
                let scale = Math.min(1, maxWidth / img.width);

                let canvas = document.createElement("canvas");
                canvas.width = img.width * scale;
                canvas.height = img.height * scale;

                let ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                // عرض الصورة المصغرة
                element.innerHTML = '<img src="' + canvas.toDataURL("image/jpeg",0.8) + '" style="max-width:100%; border-radius:10px;">';
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        alert("يرجى سحب صورة فقط");
    }
}
</script>
</body>
</html>
