@extends('layout')
@section('content')

  <div class="container">
        <h2>البحث عن شهادة برقم المعاملة</h2>

        @if(session('error'))
            <div style="color: red">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('certificates.search') }}">
            @csrf
            <label for="transaction_number">رقم المعاملة:</label>
            <input type="text" name="transaction_number" id="transaction_number" required>
            <button type="submit">بحث</button>
        </form>
    </div>

@endsection