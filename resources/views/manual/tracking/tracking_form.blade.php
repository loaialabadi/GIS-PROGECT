@extends('layout')
@section('content')
<div class="container mt-2">
    <h2>📝 إدخال بيانات شهادة تتبع</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('tracking_certificates.store') }}">
        @csrf

        <div class="mb-3">
            <label for="client_name" class="form-label">اسم العميل *</label>
            <input type="text" id="client_name" name="client_name" class="form-control" value="{{ old('client_name') }}" required>
            @error('client_name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="national_id" class="form-label">رقم البطاقة *</label>
            <input type="text" id="national_id" name="national_id" class="form-control" value="{{ old('national_id') }}" required>
            @error('national_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    <input type="hidden" id="certificate_path" name="certificate_path">


        <div class="mb-3">
            <label for="transaction_number" class="form-label">رقم المعاملة *</label>
            <input type="text" id="transaction_number" name="transaction_number" class="form-control" value="{{ old('transaction_number') }}" required>
            @error('transaction_number')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>


        <div class="mb-3">
            <label for="purpose" class="form-label">الغرض من الشهادة *</label>
            <input type="text" id="purpose" name="purpose" class="form-control" value="{{ old('purpose') }}" required>
            @error('purpose')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>


  
        <div class="mb-3">
            <label for="coordinates" class="form-label">الإحداثيات</label>
            <input type="text" id="coordinates" name="coordinates" class="form-control" value="{{ old('coordinates') }}">
            @error('coordinates')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="building_description" class="form-label">وصف المبنى</label>
            <textarea id="building_description" name="building_description" class="form-control">{{ old('building_description') }}</textarea>
            @error('building_description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="center_name" class="form-label">اسم المركز</label>
            <select id="center_name" name="center_name" class="form-control">
                <option value="">-- اختر المركز --</option>
                <option value="مركز قنا" {{ old('center_name') == 'مركز قنا' ? 'selected' : '' }}>مركز قنا</option>
                <option value="مركز دشنا" {{ old('center_name') == 'مركز دشنا' ? 'selected' : '' }}>مركز دشنا</option>
                <option value="مركز نجع حمادي" {{ old('center_name') == 'مركز نجع حمادي' ? 'selected' : '' }}>مركز نجع حمادي</option>
                <option value="مركز قوص" {{ old('center_name') == 'مركز قوص' ? 'selected' : '' }}>مركز قوص</option>
                <option value="مركز نقادة" {{ old('center_name') == 'مركز نقادة' ? 'selected' : '' }}>مركز نقادة</option>
            </select>
            @error('center_name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="area" class="form-label">المنطقة</label>
            <input type="text" id="area" name="area" class="form-control" value="{{ old('area') }}">
        </div>

 

        <div class="mb-3">
            <label for="notes" class="form-label">ملاحظات</label>
            <textarea id="notes" name="notes" class="form-control">{{ old('notes') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="inspector_name" class="form-label">اسم القائم بالمتابعة</label>
            <select id="inspector_name" name="inspector_name" class="form-control">
                <option value="">اختر الاسم</option>
                <option value="سيد عبيد" {{ old('inspector_name') == 'سيد عبيد' ? 'selected' : '' }}>سيد عبيد</option>
                <option value="الحسيني سعيد" {{ old('inspector_name') == 'الحسيني سعيد' ? 'selected' : '' }}>الحسيني سعيد</option>
                <option value="احمد عبدالرحمن" {{ old('inspector_name') == 'احمد عبدالرحمن' ? 'selected' : '' }}>احمد عبدالرحمن</option>
                <option value="محمد عبدالخالق" {{ old('inspector_name') == 'محمد عبدالخالق' ? 'selected' : '' }}>محمد عبدالخالق</option>
                <option value="مصطفي مهران" {{ old('inspector_name') == 'مصطفي مهران' ? 'selected' : '' }}>مصطفي مهران</option>
                <option value="محمد عبدالحميد" {{ old('inspector_name') == 'محمد عبدالحميد' ? 'selected' : '' }}>محمد عبدالحميد</option>
            </select>
        </div>

        <!-- حقول حالة التتبع 5 -->
@php
    $availableDates = ['8-2020', '4-2005', '2-2011', '9-2009', '7-2016',
                       '7-2017', '10-2018', '11-2020', '11-2022', '4-2023',
                       '3-2024', '3-2025'];

    $selectedTracking = old('selected_tracking', []);
    $trackingStatus = old('tracking_status', []);
@endphp


<h5>اختر 4 تواريخ فقط لإدخال وصف المتابعة:</h5>

@foreach($availableDates as $index => $date)
    <div class="mb-2 border p-2 rounded">
        <div class="form-check">
            <input 
                class="form-check-input tracking-checkbox" 
                type="checkbox" 
                value="{{ $date }}" 
                id="checkbox_{{ $index }}" 
                name="selected_tracking[]"
                {{ in_array($date, $selectedTracking) ? 'checked' : '' }}
            >
            <label class="form-check-label" for="checkbox_{{ $index }}">
                {{ $date }}
            </label>
        </div>

        <input 
            type="text" 
            class="form-control mt-2 tracking-description" 
            name="tracking_status[{{ $date }}]" 
            placeholder="أدخل وصف المتابعة"
            value="{{ $trackingStatus[$date] ?? '' }}"
            style="{{ in_array($date, $selectedTracking) ? '' : 'display:none;' }}"
        >
    </div>
@endforeach



        <!-- بيانات GIS -->
        <h5>بيانات GIS</h5>

        <div class="mb-3">
            <label for="gis_name" class="form-label">اسم مسؤول GIS</label>
            <input type="text" id="gis_name" name="gis_name" class="form-control" value="{{ old('gis_name') }}">
        </div>

  

        <!-- زر جديد للمعاينة -->
        <button type="submit" name="action" value="preview" class="btn btn-secondary"  onclick="saveCertificateTemp()">👁️ معاينة الشهادة</button>
        <button type="submit" name="action" value="save" class="btn btn-primary">💾 حفظ الشهادة</button>

    </form>





@push('scripts')


<script>
    const clientInput = document.getElementById('client_name');
    const transactionInput = document.getElementById('transaction_number');
    const pathInput = document.getElementById('certificate_path');

    function updatePath() {
        const clientName = clientInput.value.trim();
        const transactionNumber = transactionInput.value.trim();

        if(clientName && transactionNumber) {
            pathInput.value = `certificates/${transactionNumber}_${clientName}/اسم_الملف_المحفوظ.jpg`;
        }
    }

    clientInput.addEventListener('input', updatePath);
    transactionInput.addEventListener('input', updatePath);
</script>
<script>
    function saveCertificateTemp() {
    let container = document.querySelector('.container');
    html2canvas(container, { scale: 2, useCORS: true }).then(canvas => {
        canvas.toBlob(function(blob) {
            let formData = new FormData();
            formData.append("image", blob, "certificate.png");

            fetch("{{ route('tracking_certificates.save_temp_image') }}", {
                method: "POST",
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success'){
                    // حفظ المسار المؤقت في الحقل certificate_path_temp
                    document.querySelector('#certificate_path_temp').value = data.path;

                    // إرسال الفورم للمعاينة أو الحفظ النهائي
                    document.querySelector('#previewForm').submit();
                } else {
                    alert("❌ لم يتم الحفظ مؤقتاً");
                }
            });
        }, "image/png");
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.tracking-checkbox');
    const maxSelection = 4;

    function updateCheckboxState() {
        const selected = Array.from(checkboxes).filter(cb => cb.checked);
        checkboxes.forEach(cb => {
            if (!cb.checked) {
                cb.disabled = selected.length >= maxSelection;
            }
        });
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            // إظهار أو إخفاء حقل الوصف بناءً على الاختيار
            const input = this.closest('.mb-2').querySelector('.tracking-description');
            if (this.checked) {
                input.style.display = '';
            } else {
                input.style.display = 'none';
                input.value = ''; // مسح القيمة إذا ألغى التحديد
            }

            updateCheckboxState();
        });
    });

    updateCheckboxState();
});
</script>
@endpush
</div>




@endsection
