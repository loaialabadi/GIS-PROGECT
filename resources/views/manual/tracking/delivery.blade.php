@extends('layout')

@section('content')
<div class="container">
    <h2>تسليم الشهادات</h2>

    {{-- فورم البحث --}}
    <form method="GET" action="{{ url()->current() }}" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2"
               placeholder="ابحث برقم المعاملة أو اسم العميل"
               value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">بحث</button>
        <a href="{{ url()->current() }}" class="btn btn-secondary ms-2">عرض الكل</a>
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
                                    <button class="btn btn-danger btn-sm mb-1" 
                                            id="btn-receive-{{ $certificate->id }}"
                                            onclick="updateStatus({{ $certificate->id }}, 5, true)">
                                        استلام من النظم
                                    </button>
                                @else
                                    <button class="btn btn-success btn-sm mb-1" 
                                            id="btn-deliver-{{ $certificate->id }}"
                                            onclick="updateStatus({{ $certificate->id }}, 6, false)">
                                        تسليم للعميل
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted mt-3">لا توجد شهادات لهذه الحالة.</p>
    @endif
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

function updateStatus(id, status, isReceive) {
    if(!isReceive && !confirm('هل أنت متأكد من تسليم الشهادة للعميل؟')) return;

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

            // تعديل الأزرار بعد التغيير
            const btn = document.getElementById(isReceive ? 'btn-receive-' + id : 'btn-deliver-' + id);
            if(btn) btn.remove();

            if(isReceive) {
                const deliverBtn = document.createElement('button');
                deliverBtn.className = 'btn btn-success btn-sm mb-1';
                deliverBtn.id = 'btn-deliver-' + id;
                deliverBtn.innerText = 'تسليم للعميل';
                deliverBtn.onclick = function() { updateStatus(id, 6, false); };
                btn.parentNode.appendChild(deliverBtn);
            }
        } else {
            alert(data.message || 'حدث خطأ أثناء تحديث الحالة');
        }
    })
    .catch(err => console.error(err));
}
</script>
@endpush
