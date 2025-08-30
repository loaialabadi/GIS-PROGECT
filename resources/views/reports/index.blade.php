@extends('layout')

@section('content')
<div class="container">
    <h3>📊 تقارير الشهادات</h3>

<form method="GET" action="{{ route('reports.index') }}">
    <input type="text" name="client_name" placeholder="اسم العميل" value="{{ request('client_name') }}">
    <input type="date" name="from" value="{{ request('from') }}">
    <input type="date" name="to" value="{{ request('to') }}">
    <input type="text" name="inspector_name" placeholder="عمل ميداني" value="{{ request('inspector_name') }}">
    <input type="text" name="gis_preparer_name" placeholder="معد GIS" value="{{ request('gis_preparer_name') }}">
    <input type="text" name="gis_reviewer_name" placeholder="مراجع GIS" value="{{ request('gis_reviewer_name') }}">
    
    <button type="submit">بحث</button>
    <a href="{{ route('reports.export', request()->all()) }}" class="btn btn-success">⬇️ تصدير Excel</a>
</form>



    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>رقم المعاملة</th>
                <th>اسم العميل</th>
                <th>المراجع</th>
                <th>المركز</th>
                <th>القائم بالادخال</th>
                <th>الحالة</th>
                <th>تاريخ الإنشاء</th>
            </tr>
        </thead>
        <tbody>
            @forelse($certificates as $cert)
                <tr>
                    <td>{{ $cert->id }}</td>
                    <td>{{ $cert->transaction_number }}</td>
                    <td>{{ $cert->client_name }}</td>
                    <td>{{ $cert->gis_reviewer_name }}</td>
                    <td>{{ $cert->center_name }}</td>
                    <td>{{ $cert->gis_preparer_name }}</td>
                    <td>
                        @if($cert->is_delivered)
                            ✅ تم التسليم
                        @else
                            ⏳ جاري التنفيذ
                        @endif
                    </td>
                    <td>{{ $cert->created_at->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr><td colspan="8">لا توجد بيانات</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $certificates->links() }}
</div>
@endsection
