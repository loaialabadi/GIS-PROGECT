@extends('layout')

@section('content')
<div class="container">
    <h2>البحث عن شهادة برقم المعاملة</h2>

    @if(session('error'))
        <div style="color: red">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('transactions.search') }}">
        @csrf
        <label for="transaction_number">رقم المعاملة:</label>
        <input type="text" name="transaction_number" id="transaction_number" required>
        <button type="submit">بحث</button>
    </form>
</div>

<div class="container tracking-certificates-container mt-4">
    <h2>قائمة شهادات التتبع الزمني</h2>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped tracking-certificates-table">
        <thead>
            <tr>
                <th>#</th>
                <th>رقم المعاملة</th>
                <th>اسم العميل</th>
                <th>الرقم القومي</th>
                <th>الوصف</th>
                <th>المركز</th>
                <th>المساحة</th>
                <th>الحالة</th>
                <th>الملاحظات</th>
                <th>المفتش</th>
                <th>موظف GIS</th>
                <th>تاريخ الإنشاء</th>
                <th>الإجراء</th>
            </tr>
        </thead>
        <tbody>
            @foreach($certificates as $certificate)
            <tr>
                <td>{{ $certificate->id }}</td>
                <td>{{ $certificate->transaction_number }}</td>
                <td>{{ $certificate->client_name }}</td>
                <td>{{ $certificate->national_id }}</td>
                <td>{{ $certificate->building_description }}</td>
                <td>{{ $certificate->center_name }}</td>
                <td>{{ $certificate->area }}</td>
                <td>
                    <span id="status-{{ $certificate->id }}">
                        {{ $certificate->delivery_status }}
                    </span>
                </td>
                <td>{{ $certificate->notes }}</td>
                <td>{{ $certificate->inspector_name }}</td>
                <td>{{ $certificate->gis_name }}</td>
                <td>{{ $certificate->created_at }}</td>
                <td>
                    <div class="btn-group flex-wrap">
                        <a href="{{ route('tracking_certificates.edit', $certificate->id) }}" class="btn btn-warning btn-sm mb-1">
                            📝 مراجعة
                        </a>

                        <a href="{{ route('tracking_certificates.images', $certificate->id) }}" class="btn btn-info btn-sm mb-1" target="_blank">
                            🖼 عرض الصور
                        </a>

                        <!-- أزرار الحالات المختلفة -->
                        <button class="btn btn-primary btn-sm mb-1" onclick="updateStatus({{ $certificate->id }}, 1)">
                            في صفحة المراجعة
                        </button>
                        <button class="btn btn-success btn-sm mb-1" onclick="updateStatus({{ $certificate->id }}, 2)">
                            استيفاء
                        </button>
                        <button class="btn btn-warning btn-sm mb-1" onclick="updateStatus({{ $certificate->id }}, 3)">
                            تمت المراجعة
                        </button>
                        <button class="btn btn-secondary btn-sm mb-1" onclick="updateStatus({{ $certificate->id }}, 4)">
                            استلام من النظم
                        </button>
                        <button class="btn btn-dark btn-sm mb-1" onclick="updateStatus({{ $certificate->id }}, 5)">
                            تسليم للعميل
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
const statusText = {
    1: 'في صفحة المراجعة',
    2: 'استيفاء',
    3: 'تمت المراجعة',
    4: 'استلام من النظم',
    5: 'تم التسليم للعميل'
};

function updateStatus(id, status) {
    fetch(`/tracking-certificates/${id}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: status })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            document.getElementById('status-' + id).innerText = statusText[data.newStatus] || data.newStatus;
        } else {
            alert(data.message || 'حدث خطأ أثناء تحديث الحالة');
        }
    })
    .catch(err => console.error(err));
}
</script>
@endpush
