@extends('layout')

@section('content')
<div class="container">
    <h2>تسليم الشهادات</h2>

    <form method="GET" action="{{ route('tracking_certificates.delivery', ['status' => 'pending']) }}">
        <label for="transaction_number">رقم المعاملة:</label>
        <input type="text" name="transaction_number" id="transaction_number" value="{{ request('transaction_number') }}">
        <button type="submit" class="btn btn-primary">بحث</button>
        <a href="{{ route('tracking_certificates.delivery', ['status' => 'pending']) }}" class="btn btn-secondary">عرض الكل</a>
    </form>

    @if(count($certificates) > 0)
        <table class="table table-bordered text-center align-middle mt-3">
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
                @php
                    $statusLabels = [
                        1 => 'جاري',
                        2 => 'في الاستيفاء',
                        3 => 'وجاري المراجعة مرة أخرى بعد الاستيفاء',
                        4 => 'تم التسليم إلى خدمة العملاء',
                        5 => 'تم الاستلام من النظم',
                        6 => 'تم التسليم للعميل',
                    ];
                @endphp

                @foreach($certificates as $certificate)
                    @if(!request('transaction_number') || $certificate->transaction_number == request('transaction_number'))
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
                            <td>{{ $certificate->created_at }}</td>
                            <td>
                                <div class="btn-group flex-wrap">
                                    @if($certificate->delivery_status != 5)
                                        <button class="btn btn-danger btn-sm mb-1" id="btn-receive-{{ $certificate->id }}"
                                            onclick="receiveFromSystem({{ $certificate->id }})">
                                            استلام من النظم
                                        </button>
                                    @endif

                                    @if($certificate->delivery_status == 5)
                                        <button class="btn btn-success btn-sm mb-1" id="btn-deliver-{{ $certificate->id }}"
                                            onclick="deliverToClient({{ $certificate->id }})">
                                            تسليم للعميل
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted mt-3">لا توجد شهادات لهذه الحالة.</p>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">🔙 العودة</a>
</div>
@endsection

@push('scripts')
<script>
const statusText = {
    1: 'جاري',
    2: 'في الاستيفاء',
    3: 'وجاري المراجعة مرة أخرى بعد الاستيفاء',
    4: 'تم التسليم إلى خدمة العملاء',
    5: 'تم الاستلام من النظم',
    6: 'تم التسليم للعميل',
};

function receiveFromSystem(id) {
    fetch(`/tracking-certificates/${id}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: 5 })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('status-' + id).innerText = statusText[5];
            const btn = document.getElementById('btn-receive-' + id);
            if (btn) btn.remove();

            const deliverBtn = document.createElement('button');
            deliverBtn.className = 'btn btn-success btn-sm mb-1';
            deliverBtn.id = 'btn-deliver-' + id;
            deliverBtn.innerText = 'تسليم للعميل';
            deliverBtn.onclick = function() { deliverToClient(id); };
            btn.parentNode.appendChild(deliverBtn);
        } else {
            alert(data.message || 'حدث خطأ أثناء تحديث الحالة');
        }
    })
    .catch(err => console.error(err));
}

function deliverToClient(id) {
    if(!confirm('هل أنت متأكد من تسليم الشهادة للعميل؟')) return;

    fetch(`/tracking-certificates/${id}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: 6 })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('status-' + id).innerText = statusText[6];
            const btn = document.getElementById('btn-deliver-' + id);
            if (btn) btn.remove();
        } else {
            alert(data.message || 'حدث خطأ أثناء تحديث الحالة');
        }
    })
    .catch(err => console.error(err));
}
</script>
@endpush
