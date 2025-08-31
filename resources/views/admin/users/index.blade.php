@extends('layout')

@section('content')
<h2>المستخدمين</h2>
<a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">إضافة مستخدم جديد</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>الاسم</th>
            <th>البريد الإلكتروني</th>
            <th>الدور</th>
            <th>الإجراءات</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
<td>
    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">تعديل</a>

    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
    </form>

    <form action="{{ route('admin.users.toggle', $user) }}" method="POST" style="display:inline;">
        @csrf
        @method('PATCH')
        @if($user->is_active)
            <button type="submit" class="btn btn-sm btn-secondary">تعطيل</button>
        @else
            <button type="submit" class="btn btn-sm btn-success">تنشيط</button>
        @endif
    </form>
</td>


        </tr>
        @endforeach
    </tbody>
</table>
@endsection
