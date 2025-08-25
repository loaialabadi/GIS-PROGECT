@extends('layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">صور شهادة العميل: <strong>{{ $certificate->client_name }}</strong></h2>

    @if(count($images) > 0)
        <div class="row g-4">
            @foreach($images as $img)
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card shadow-sm h-100 rounded">
                        {{-- عرض الصورة --}}
                        <img src="{{ asset('storage/' . $img) }}" 
                             alt="صورة الشهادة" 
                             class="card-img-top" 
                             style="object-fit: cover; height: 300px; border-bottom: 1px solid #dee2e6;">

                        <div class="card-body d-flex flex-column">
                            {{-- رابط التحميل أو العرض --}}
                            <a href="{{ asset('storage/' . $img) }}" target="_blank" class="btn btn-primary btn-sm mb-2 w-100">
                                🔗 عرض / تحميل الصورة
                            </a>

                            {{-- زر الحذف --}}
                            <form action="{{ route('certificates.deleteImage', ['id' => $certificate->id]) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="image" value="{{ $img }}">
                                <button type="submit" class="btn btn-danger btn-sm w-100">🗑 حذف</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-muted mt-4">لا توجد صور لهذه الشهادة.</p>
    @endif

    {{-- زر العودة --}}
    <div class="mt-4 text-center">
        <a href="{{ route('certificates.search.form') }}" class="btn btn-outline-primary">
            🔙 العودة للبحث
        </a>
    </div>
</div>
@endsection
