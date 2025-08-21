@extends('layout')

@section('content')
<div class="container">
    <h2>صور شهادة {{ $certificate->client_name }}</h2>

    @if(count($images) > 0)
        <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: flex-start;">
            @foreach($images as $img)
                <div style="border: 1px solid #ccc; padding: 5px; flex: 1 1 45%; max-width: 600px; position: relative;">
                    <img src="{{ $img }}" style="width: 100%; height: auto; display: block;">

                    {{-- زر الحذف --}}
                    <form action="{{ route('certificates.deleteImage', ['id' => $certificate->id]) }}" 
                          method="POST" 
                          style="margin-top: 10px; text-align: right;">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="image" value="{{ $img }}">
                        <button type="submit" class="btn btn-danger btn-sm">🗑 حذف الصورة</button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <p>لا توجد صور لهذه الشهادة.</p>
    @endif

    {{-- زر الطباعة --}}
    <button onclick="window.print()" class="btn btn-primary mt-3">🖨 طباعة</button>

    <a href="{{ route('certificates.search.form') }}" class="btn btn-outline-primary mt-3">🔙 العودة للبحث</a>
</div>
@endsection
