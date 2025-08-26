@extends('layout')

@section('content')
<div class="container">
    <h2>الشهادات التي لم يتم مراجعتها</h2>

    {{-- فورم البحث --}}
    <form method="GET" action="{{ route('tracking.review') }}" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" 
               placeholder="ابحث برقم المعاملة أو اسم العميل"
               value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">بحث</button>
        <a href="{{ route('tracking.review') }}" class="btn btn-secondary ms-2">عرض الكل</a>
    </form>

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
                        <td>{{ $certificate->owner_name }}</td>
                        <td>
                            <span id="status-{{ $certificate->id }}">
                                {{ $certificate->delivery_status }}
                            </span>
                        </td>
                        <td>{{ $certificate->notes }}</td>
                        <td>{{ $certificate->created_at }}</td>
                        <td>
                            <div class="btn-group mb-1">
                                <a href="{{ route('tracking_certificates.edit', $certificate->id) }}" class="btn btn-warning btn-sm">
                                    تعديل بيانات
                                </a>

                                <button class="btn btn-primary btn-sm" onclick="updateStatus({{ $certificate->id }}, 2)">
                                    استيفاء
                                </button>
                                <button class="btn btn-success btn-sm" onclick="updateStatus({{ $certificate->id }}, 3)">
                                    تمت المراجعة
                                </button>
                                <button class="btn btn-info btn-sm" onclick="updateStatus({{ $certificate->id }}, 4)">
                                    تسليم إلى خدمة العملاء
                                </button>
                                <a href="{{ route('certificates.showImages', $certificate->id) }}" class="btn btn-dark btn-sm" target="_blank">
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
const statusText = {
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
