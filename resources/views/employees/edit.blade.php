@extends('layout')

@section('content')
<div class="container">
    <h2>تعديل الموظف</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('employees.update', $employee->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">اسم الموظف</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $employee->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">القسم</label>
            <select name="role" id="role" class="form-control" required>
                <option value="">اختر القسم</option>
                <option value="inspector" {{ old('role', $employee->role) == 'inspector' ? 'selected' : '' }}>مفتش</option>
                <option value="gis_preparer" {{ old('role', $employee->role) == 'gis_preparer' ? 'selected' : '' }}>معد GIS</option>
                <option value="gis_reviewer" {{ old('role', $employee->role) == 'gis_reviewer' ? 'selected' : '' }}>مراجع GIS</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">تعديل</button>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">رجوع</a>
    </form>
</div>
@endsection
