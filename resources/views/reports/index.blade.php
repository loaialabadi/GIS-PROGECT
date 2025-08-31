@extends('layout')

@section('content')
<div class="container">
    <h3>📊 تقارير الشهادات</h3>
<form method="GET" action="{{ route('reports.index') }}">


    {{-- من تاريخ --}}
    <div class="mb-3">
        <label for="from" class="form-label">من تاريخ</label>
        <input type="date" id="from" name="from" class="form-control" value="{{ request('from') }}">
    </div>

    {{-- إلى تاريخ --}}
    <div class="mb-3">
        <label for="to" class="form-label">إلى تاريخ</label>
        <input type="date" id="to" name="to" class="form-control" value="{{ request('to') }}">
    </div>

    {{-- القائم بالمتابعة --}}
    <div class="mb-3">
        <label for="inspector_name" class="form-label">اسم القائم بالمتابعة</label>
        <select id="inspector_name" name="inspector_name" class="form-control">
            <option value="">اختر الاسم</option>
            @foreach(\App\Models\Employee::where('role','inspector')->get() as $emp)
                <option value="{{ $emp->name }}" {{ request('inspector_name') == $emp->name ? 'selected' : '' }}>
                    {{ $emp->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- GIS إعداد --}}
    <div class="mb-3">
        <label for="gis_preparer_name" class="form-label">اسم مسؤول GIS إعداد</label>
        <select id="gis_preparer_name" name="gis_preparer_name" class="form-control">
            <option value="">اختر الموظف</option>
            @foreach(\App\Models\Employee::where('role','gis_preparer')->get() as $emp)
                <option value="{{ $emp->name }}" {{ request('gis_preparer_name') == $emp->name ? 'selected' : '' }}>
                    {{ $emp->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- GIS مراجعة --}}
    <div class="mb-3">
        <label for="gis_reviewer_name" class="form-label">اسم مسؤول GIS مراجعة</label>
        <select id="gis_reviewer_name" name="gis_reviewer_name" class="form-control">
            <option value="">اختر الموظف</option>
            @foreach(\App\Models\Employee::where('role','gis_reviewer')->get() as $emp)
                <option value="{{ $emp->name }}" {{ request('gis_reviewer_name') == $emp->name ? 'selected' : '' }}>
                    {{ $emp->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- أزرار البحث والتصدير --}}
    <div class="mb-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary">بحث</button>
        <a href="{{ route('reports.export', request()->all()) }}" class="btn btn-success">⬇️ تصدير Excel</a>
    </div>
</form>




    <table class="table table-bordered table-hover text-center align-middle">
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
