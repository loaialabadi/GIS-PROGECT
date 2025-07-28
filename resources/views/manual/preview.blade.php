<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>شهادة تتبع زمني لمنشأ</title>
<style>
  body {
    font-family: 'Arial', sans-serif;
    background: #eee;
    margin: 0; padding: 20px;
  }
  .container {
    display: flex;
    max-width: 1200px;
    margin: auto;
    background: #fff;
    border: 2px solid black;
    padding: 15px;
    box-sizing: border-box;
  }

  /* العمود الأيمن (33%) */
  .right-column {
    flex: 1;
    border-left: 2px solid black;
    padding-left: 15px;
    display: flex;
    flex-direction: column;
  }

.logos {
  display: flex;
  justify-content: space-between;
  gap: 40px; /* المسافة بين اللوجوهات */
  margin-bottom: 20px;
}

.logo-box {
  text-align: center;
}

.logo-title {
  font-weight: bold;
  margin-bottom: 5px;
  font-size: 14px;
}

.logo {
  width: 60px;
  height: 60px;
  object-fit: contain;
  border: 1px solid black;
}


  /* الجداول */
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

  /* العمود الأوسط والأيسر (66%) */
  .left-columns {
    flex: 2;
    display: flex;
    flex-direction: column;
    padding-right: 15px;
  }

  /* عنوان الشهادة */
  .certificate-title {
    font-size: 24px;
    font-weight: bold;
    text-align: center;
    background: #fde5a6;
    padding: 10px;
    border: 1px solid black;
    margin-bottom: 15px;
  }

  /* 4 صور متوزعة */
 .photos-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-gap: 15px;
    margin-bottom: 15px;
    height: 600px; /* زود الارتفاع حسب الحاجة */
}
  .photo-box {
        height: 290px; /* تقريبا نصف ارتفاع الحاوية مع بعض الفراغ */

    border: 2px dashed #aaa;
    border-radius: 8px;
    background: #f9f9f9;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #888;
    font-size: 14px;
    cursor: pointer;
    overflow: hidden;
  }

.photo-label {
    position: absolute;
    top: 0;  /* في أعلى الصورة */
    left: 0;
    width: 100%;  /* يعرض النص بعرض الصورة */
    background-color: rgba(0, 0, 0, 0.5); /* خلفية شفافة سوداء */
    color: #fff;
    padding: 5px 10px;
    font-size: 14px;
    font-weight: bold;
    box-sizing: border-box;
}
  .photo-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
        display: block;

  }

  /* باقي محتوى الشهادة */
  .content-area {
    padding: 10px;
    border: 1px solid black;
    min-height: 200px;
    font-size: 16px;
    line-height: 1.4;
  }

  /* النص النهائي تحت كل شيء */
  .footer-text {
    max-width: 1200px;
    margin: 20px auto 0;
    font-size: 13px;
    text-align: center;
    border-top: 1px solid black;
    padding-top: 10px;
    color: #555;
    line-height: 1.3;
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




    <!-- جدول بيانات مقدم الطلب -->
<!-- جدول بيانات مقدم الطلب -->
<table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">
  <tr><td>اسم العميل</td><td>{{ $data['client_name'] ?? '' }}</td></tr>
  <tr><td>الرقم القومي</td><td>{{ $data['national_id'] ?? '' }}</td></tr>
    <tr><td>المركز</td><td>{{ $data['center_name'] ?? '' }}</td></tr>
    <tr><td>رقم المعاملة</td><td>{{ $data['transaction_number'] ?? '' }}</td></tr>
  <tr><td>الغرض من الشهادة</td><td>{{ $data['certificate_purpose'] ?? '' }}</td></tr>
  <tr><td>الاحداثي</td><td>{{ $data['coordinates'] ?? '' }}</td></tr>
</table>

<!-- جدول موقف التتبع -->
<h5>حالة التتبع</h5>

@php
    $trackingStatus = $data['tracking_status'] ?? [];
@endphp

<table border="1" cellspacing="0" cellpadding="5" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>التاريخ</th>
            <th>الموقف</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($trackingStatus as $date => $status)
            <tr>
                <td>{{ $date }}</td>
                <td>{{ $status ?: '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>



<!-- جدول التوقيعات -->

<table border="1" cellspacing="0" cellpadding="10" style="border-collapse: collapse; width: 100%; margin-top: 20px; text-align: center; font-size: 14px;">
  <tr>
    <th>خدمة عملاء</th>
    <th>أعمال رفع ميداني</th>
    <th>أعمال GIS</th>
  </tr>
  <tr>
    <td>
      <div><strong>الاسم:</strong> منى عبدالفتاح</div><br>
      <div style="border-top: 1px solid #000; margin-top: 10px;"><strong>التوقيع:.........</strong> {{ $data['customer_service_sign'] ?? '' }}</div>
    </td>
    <td>
      <div><strong>الاسم:</strong> {{ $data['inspector_name'] ?? '' }}</div><br>
      <div style="border-top: 1px solid #000; margin-top: 10px;"><strong>التوقيع:..........</strong> {{ $data['field_survey_sign'] ?? '' }}</div>
    </td>
    <td>
      <div><strong>الاسم:</strong> {{ $data['gis_name'] ?? '' }}</div><br>
      <div style="border-top: 1px solid #000; margin-top: 10px;"><strong>التوقيع:.........</strong> {{ $data['gis_sign'] ?? '' }}</div>
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





  </div>

  <!-- العمود الأوسط والأيسر -->
  <div class="left-columns">
    <div class="certificate-title">📄 شهادة تتبع زمني لمنشأ</div>

    <!-- 4 صور -->
<div class="photos-container">
  <div class="photo-box" ondrop="drop(event, this)" ondragover="allowDrop(event)" style="position: relative;">
    <div class="photo-label">صورة بتاريخ 27-07-2025</div>
    📷 اسحب الصورة هنا
  </div>

  <div class="photo-box" ondrop="drop(event, this)" ondragover="allowDrop(event)" style="position: relative;">
    <div class="photo-label">صورة بتاريخ 27-07-2025</div>
    📷 اسحب الصورة هنا
  </div>

  <div class="photo-box" ondrop="drop(event, this)" ondragover="allowDrop(event)" style="position: relative;">
    <div class="photo-label">صورة بتاريخ 27-07-2025</div>
    📷 اسحب الصورة هنا
  </div>

  <div class="photo-box" ondrop="drop(event, this)" ondragover="allowDrop(event)" style="position: relative;">
    <div class="photo-label">صورة بتاريخ 27-07-2025</div>
    📷 اسحب الصورة هنا
  </div>
</div>

    <!-- باقي محتوى الشهادة -->

  </div>

</div>

<!-- نص ختامي أسفل الصفحة -->
<div class="footer-text">
  تم الإرشاد عن الموقع بمعرفة مقدم الطلب وذلك دون أدنى مسؤولية على مركز معلومات شبكات المرافق بقنا - لا يعتد بهذا البيان كمستند ملكية - لا يستخدم هذا البيان إلا في الغرض المحرر من أجله.
</div>

<script>
  function allowDrop(ev) {
      ev.preventDefault();
  }
  function drop(ev, element) {
      ev.preventDefault();
      const file = ev.dataTransfer.files[0];

      if (file && file.type.startsWith("image/")) {
          const reader = new FileReader();
          reader.onload = function(e) {
              element.innerHTML = '<img src="' + e.target.result + '" alt="صورة">';
          }
          reader.readAsDataURL(file);
      } else {
          alert("يرجى سحب صورة فقط");
      }
  }





 



</script>

</body>
</html>
