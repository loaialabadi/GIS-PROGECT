@extends('layout')

@section('content')
<div class="container">
    <h2>الموظفين</h2>
    <a href="{{ route('employees.create') }}" class="btn btn-primary mb-2">إضافة موظف جديد</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th>الاسم</th>
            <th>القسم</th>
            <th>العمليات</th>
        </tr>
        @foreach($employees as $emp)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $emp->name }}</td>
            <td>{{ $emp->role }}</td>
            <td>
                <a href="{{ route('employees.edit', $emp->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                <form action="{{ route('employees.destroy', $emp->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
