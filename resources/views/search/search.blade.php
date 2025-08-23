@extends('layout')

@section('content')


@if(isset($results) && $results->count() > 0)
    <!-- جدول النتائج -->
@elseif(isset($results))
    <p class="text-muted">لا توجد نتائج لهذا البحث.</p>
@endif
<div class="container">
    <h2>البحث عن شهادة برقم المعاملة</h2>

    @if(session('error'))
        <div style="color: red">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('certificates.search') }}" class="mb-4">
        @csrf
        <label for="transaction_number">رقم المعاملة:</label>
        <input type="text" name="transaction_number" id="transaction_number" value="{{ old('transaction_number') }}" required>
        <button type="submit" class="btn btn-primary btn-sm">بحث</button>
    </form>

    @if(isset($results) && $results->count() > 0)
        <h2>نتائج البحث</h2>

        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>رقم المعاملة</th>
                    <th>الاسم</th>
                    <th>المركز</th>
                    <th>الحالة الحالية</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $certificate)
    @php
    $statusLabels = [
        1 => 'جاري',
        2 => 'في الاستيفاء',
        3 => 'وجاري المراجعة مرة أخرى بعد الاستيفاء',
        4 => 'تم التسليم إلى خدمة العملاء',
        5 => 'تم الاستلام من النظم',
        6 => 'تم التسليم للعميل',
    ];


    $statuses = $certificate->delivery_status;

    // لو القيمة ليست مصفوفة، نحولها لمصفوفة
    if (is_null($statuses)) {
        $statuses = [];
    } elseif (is_string($statuses)) {
        $statuses = array_map('trim', explode(',', $statuses));
    } elseif (is_int($statuses)) {
        $statuses = [$statuses];
    }

    // آخر حالة
    $currentStatus = !empty($statuses) ? end($statuses) : null;

    // الاسم النهائي للحالة
    $currentStatusLabel = $currentStatus !== null
        ? ($statusLabels[$currentStatus] ?? $currentStatus)
        : 'لا توجد بيانات';
@endphp




                    <tr>
                        <td>{{ $certificate->id }}</td>
                        <td>{{ $certificate->transaction_number }}</td>
                        <td>{{ $certificate->client_name }}</td>
                        <td>{{ $certificate->center_name }}</td>
                        <td>{{ $currentStatusLabel }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif(isset($results))
        <p class="text-muted">لا توجد نتائج لهذا البحث.</p>
    @endif
</div>
@endsection
