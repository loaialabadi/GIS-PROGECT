@extends('layout')

@section('content')
<div class="container">
    <h2>الشهادات  التي لم يتم مراجعتها</h2>

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
                            <div class="btn-group">
                                <a href="{{ route('tracking_certificates.edit', $certificate->id) }}" 
                                   class="btn btn-warning btn-sm">
                                    تعديل بيانات
                                </a>
                                <button class="btn btn-success btn-sm" onclick="updateStatus({{ $certificate->id }}, 1)">
                                    ➕
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="updateStatus({{ $certificate->id }}, -1)">
                                    ➖
                                </button>

            <a href="{{ route('tracking_certificates.images', $certificate->id) }}" class="btn btn-info" target="_blank">
                🖼 عرض الصور
            </a>

                            </div>
                        </td>
                    </tr>
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
        } else {
            alert('حدث خطأ أثناء تحديث الحالة');
        }
    })
    .catch(err => console.error(err));
}
</script>
@endpush
