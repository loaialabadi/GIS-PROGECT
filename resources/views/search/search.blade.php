@extends('layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">البحث عن شهادة برقم المعاملة</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- فورم البحث --}}
    <form method="POST" action="{{ route('certificates.search') }}" class="mb-4 d-flex gap-2">
        @csrf
        <input type="text" name="transaction_number" class="form-control" placeholder="ادخل رقم المعاملة" value="{{ old('transaction_number') }}" required>
        <button type="submit" class="btn btn-primary">🔍 بحث</button>
        <a href="{{ route('certificates.search.form') }}" class="btn btn-secondary">عرض الكل</a>
    </form>

    {{-- عرض النتائج --}}
    @if(isset($results) && $results->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>رقم المعاملة</th>
                        <th>الاسم</th>
                        <th>المركز</th>
                        <th>القائم بالرفع</th>
                        <th>مراجع GIS</th>
                        <th>إعداد GIS</th>
                        <th>الحالة الحالية</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $statusLabels = [
                            1 => ['label'=>'جاري','class'=>'text-primary'],
                            2 => ['label'=>'في الاستيفاء','class'=>'text-warning'],
                            3 => ['label'=>'تمت المراجعة','class'=>'text-success'],
                            4 => ['label'=>'تم التسليم إلى خدمة العملاء','class'=>'text-secondary'],
                            5 => ['label'=>'تم الاستلام من النظم','class'=>'text-info'],
                            6 => ['label'=>'تم التسليم للعميل','class'=>'text-dark'],
                        ];
                    @endphp

                    @foreach($results as $certificate)
                        @php
                            $currentStatus = $certificate->delivery_status;
                            $currentStatusLabel = $statusLabels[$currentStatus]['label'] ?? $currentStatus;
                            $statusClass = $statusLabels[$currentStatus]['class'] ?? '';
                        @endphp
                        <tr>
                            <td>{{ $certificate->id }}</td>
                            <td>{{ $certificate->transaction_number }}</td>
                            <td>{{ $certificate->client_name }}</td>
                            <td>{{ $certificate->center_name }}</td>
                            <td>{{ $certificate->inspector_name ?? '-' }}</td>
                            <td>{{ $certificate->gis_reviewer_name ?? '-' }}</td>
                            <td>{{ $certificate->gis_preparer_name ?? '-' }}</td>
                            <td class="{{ $statusClass }}">{{ $currentStatusLabel }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif(isset($results))
        <p class="text-center text-muted">لا توجد نتائج لهذا البحث.</p>
    @endif
</div>
@endsection
