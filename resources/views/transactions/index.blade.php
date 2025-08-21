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

<div class="container tracking-certificates-container">
    <h2>قائمة شهادات التتبع الزمني</h2>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <table class="tracking-certificates-table">
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
            @if(is_array($certificate->tracking_status))
                @php
                    $filteredTracking = array_filter($certificate->tracking_status, fn($status) => !empty($status));
                @endphp
                @if(count($filteredTracking) > 0)
                    <ul class="tracking-status-list">
                        @foreach($filteredTracking as $date => $status)
                            <li><strong>{{ $date }}:</strong> {{ $status }}</li>
                        @endforeach
                    </ul>
                @else
                    <span>لا يوجد بيانات تتبع</span>
                @endif
            @else
                <span>لا يوجد بيانات تتبع</span>
            @endif
        </td>
        <td>{{ $certificate->notes }}</td>
        <td>{{ $certificate->inspector_name }}</td>
        <td>{{ $certificate->gis_name }}</td>
        <td>{{ $certificate->created_at }}</td>
        <td>
            <a href="{{ route('tracking_certificates.edit', $certificate->id) }}" class="btn btn-warning">
                📝 مراجعة
            </a>

            <a href="{{ route('tracking_certificates.images', $certificate->id) }}" class="btn btn-info" target="_blank">
                🖼 عرض الصور
            </a>

                <span id="status-{{ $certificate->id }}">{{ $certificate->delivery_status }}</span>

    <button onclick="updateStatus({{ $certificate->id }}, 1)">➕</button>
    <button onclick="updateStatus({{ $certificate->id }}, -1)">➖</button>
        </td>
    </tr>
    @endforeach
</tbody>

    </table>
</div>
@endsection
@push('scripts')
<script>
function updateStatus(id, change) {
    fetch(`/tracking-certificates/${id}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ change: change })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('status-' + id).innerText = data.newStatus;
        }
    })
    .catch(err => console.error(err));
}
</script>
@endpush


