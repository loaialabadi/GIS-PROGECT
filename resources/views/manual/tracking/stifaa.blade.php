@extends('layout')

@section('content')
<div class="container mt-4">
    <h2>الشهادات التي بها استيفاء</h2>

    {{-- فورم البحث --}}
    <form method="GET" action="{{ url()->current() }}" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2"
               placeholder="ابحث برقم المعاملة أو اسم العميل"
               value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">بحث</button>
        <a href="{{ url()->current() }}" class="btn btn-secondary ms-2">عرض الكل</a>
    </form>

    @if(count($certificates) > 0)
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>رقم المعاملة</th>
                    <th>اسم العميل</th>
                    <th>الحالة</th>
                    <th>الملاحظات</th>
                    <th>المراجع</th>
                    <th>الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $statusLabels = [
                        1 => 'في صفحة المراجعة',
                        2 => 'استيفاء',
                        3 => 'تمت المراجعة',
                        4 => 'تم التسليم إلى خدمة العملاء'
                    ];
                @endphp

                @foreach($certificates as $certificate)
                    <tr>
                        <td>{{ $certificate->id }}</td>
                        <td>{{ $certificate->transaction_number }}</td>
                        <td>{{ $certificate->client_name }}</td>
                        <td>
                            <span id="status-{{ $certificate->id }}">
                                {{ $statusLabels[$certificate->delivery_status] ?? 'لا توجد بيانات' }}
                            </span>
                        </td>
                        <td>{{ $certificate->notes }}</td>
                        <td>{{ $certificate->gis_reviewer_name }}</td>
                        <td>
                            <div class="btn-group flex-wrap">
                                <a href="{{ route('tracking_certificates.edit', $certificate->id) }}" class="btn btn-warning btn-sm mb-1">
                                    تعديل بيانات
                                </a>

                                @if($certificate->delivery_status != 3)
                                    <button class="btn btn-success btn-sm mb-1" 
                                            onclick="updateStatus({{ $certificate->id }}, 3)">
                                        تمت المراجعة
                                    </button>
                                @endif

                                <a href="{{ route('certificates.showImages', $certificate->id) }}" class="btn btn-info btn-sm mb-1" target="_blank">
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
            document.getElementById('status-' + id).innerText = statusText[status];
        } else {
            alert(data.message || 'حدث خطأ أثناء تحديث الحالة');
        }
    })
    .catch(err => console.error(err));
}
</script>
@endpush
