@extends('layout')

@section('content')

<div class="container">
    <h2>البحث عن شهادة برقم المعاملة</h2>

    <form method="GET" action="{{ route('tracking_certificates.stifaa') }}">
        <label for="transaction_number">رقم المعاملة:</label>
        <input type="text" name="transaction_number" id="transaction_number" value="{{ request('transaction_number') }}">
        <button type="submit" class="btn btn-primary">بحث</button>
        <a href="{{ route('tracking_certificates.stifaa') }}" class="btn btn-secondary">عرض الكل</a>
    </form>
</div>

<div class="container mt-4">
    <h2>الشهادات التي بها استيفاء</h2>

    @if(count($certificates) > 0)
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>رقم المعاملة</th>
                    <th>اسم العميل</th>
                    <th>الحالة</th>
                    <th>الملاحظات</th>
                    <th>تاريخ الإنشاء</th>
                    <th>الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @foreach($certificates as $certificate)
                    @if(!request('transaction_number') || $certificate->transaction_number == request('transaction_number'))
                        <tr>
                            <td>{{ $certificate->id }}</td>
                            <td>{{ $certificate->transaction_number }}</td>
                            <td>{{ $certificate->client_name }}</td>
                            <td>
                                <span id="status-{{ $certificate->id }}">
                                    {{ $certificate->delivery_status }}
                                </span>
                            </td>
                            <td>{{ $certificate->notes }}</td>
                            <td>{{ $certificate->created_at }}</td>
                            <td>
                                <div class="btn-group flex-wrap">
                                    <a href="{{ route('tracking_certificates.edit', $certificate->id) }}" class="btn btn-warning btn-sm mb-1">
                                        تعديل بيانات
                                    </a>

                                    <button class="btn btn-success btn-sm mb-1" onclick="updateStatus({{ $certificate->id }}, 3)">
                                        تمت المراجعة
                                    </button>

                                    <a href="{{ route('tracking_certificates.images', $certificate->id) }}" class="btn btn-info btn-sm mb-1" target="_blank">
                                        🖼 عرض الصور
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">لا توجد شهادات لهذه الحالة.</p>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">🔙 العودة</a>
</div>

@endsection

@push('scripts')
<script>
const statusText = {
    1: 'في صفحة المراجعة',
    2: 'استيفاء',
    3: 'تمت المراجعة',
    4: 'تم التسليم إلى خدمة العملاء'
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
        if (data.success) {
            document.getElementById('status-' + id).innerText = statusText[data.newStatus] || data.newStatus;
        } else {
            alert(data.message || 'حدث خطأ أثناء تحديث الحالة');
        }
    })
    .catch(err => console.error(err));
}
</script>
@endpush
