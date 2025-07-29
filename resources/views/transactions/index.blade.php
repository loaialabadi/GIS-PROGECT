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
                <th>رابط الشهادة</th>
                <th>الملاحظات</th>
                <th>المفتش</th>
                <th>موظف GIS</th>
                <th>تاريخ الإنشاء</th>
                <th>التسليم</th>
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
                <td>
                    @if($certificate->certificate_path)
                        <a href="{{ asset('storage/' . $certificate->certificate_path) }}" target="_blank">عرض</a>
                    @else
                        لا يوجد
                    @endif
                </td>
                <td>{{ $certificate->notes }}</td>
                <td>{{ $certificate->inspector_name }}</td>
                <td>{{ $certificate->gis_name }}</td>
                <td>{{ $certificate->created_at }}</td>
                <td>
                    @php
                        $statusMap = [
                            0 => 'لم يتم التسليم',
                            1 => 'تم التسليم من المفتش',
                            2 => 'تم التسليم من موظف GIS',
                            3 => 'تم التسليم النهائي'
                        ];
                    @endphp
                    {{ $statusMap[$certificate->delivery_status] ?? 'غير معروف' }}
                </td>
                <td>
                    @if($certificate->delivery_status < 3)
                        <form method="POST" action="{{ route('transactions.deliver', $certificate->id) }}">
                            @csrf
                            <input type="hidden" name="status" value="{{ $certificate->delivery_status + 1 }}">
                            <button type="submit" class="deliver-btn" onclick="return confirm('تأكيد الانتقال للخطوة التالية؟')">
                                تسليم للمرحلة التالية
                            </button>
                        </form>
                    @else
                        {{ \Carbon\Carbon::parse($certificate->delivered_at)->format('Y-m-d H:i') }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
