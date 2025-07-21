@extends('layout')
@section('content')

@php
use Illuminate\Support\Str;
@endphp

<h2>📋 عرض بيانات Excel</h2>

@if(isset($headers) && isset($records) && count($records) > 0)
<div style="overflow-x: auto; max-width: 100%;">
    <table style="min-width: max-content; border-collapse: collapse;">
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th style="padding: 8px; border: 1px solid #ccc;">{{ $header }}</th>
                @endforeach
                <th style="padding: 8px; border: 1px solid #ccc;">🎓 شهادة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $row)
                <tr>
                    @foreach($row as $index => $cell)
                        @php
                            $key = $headers[$index];
                            $isPolygon = Str::contains($cell, 'POLYGON');
                            $polygon = str_replace('٫', '.', $cell);

                            if ($isPolygon && preg_match('/POLYGON Z *\((.+?)\)/', $polygon, $matches)) {
                                $points = explode(',', $matches[1]);
                                $sumLng = 0;
                                $sumLat = 0;
                                $count = count($points);

                                foreach ($points as $point) {
                                    $coords = preg_split('/\s+/', trim($point));
                                    $sumLng += floatval($coords[0]); // longitude
                                    $sumLat += floatval($coords[1]); // latitude
                                }

                                $avgLng = $sumLng / $count;
                                $avgLat = $sumLat / $count;
                            }
                        @endphp

                        <td style="padding: 8px; border: 1px solid #eee;">
                            @if($isPolygon)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $avgLat }},{{ $avgLng }}" target="_blank">
                                    عرض موقع في المنتصف
                                </a>
                            @else
                                {{ $cell }}
                            @endif
                        </td>
                    @endforeach

                    <td style="padding: 8px; border: 1px solid #eee;">
                        <form method="POST" action="{{ route('placemarks.certificate.preview') }}" target="_blank">
                            @csrf
                            @foreach($headers as $index => $key)
                                <input type="hidden" name="data[{{ $key }}]" value="{{ $row[$index] ?? '' }}">
                            @endforeach
                            <button type="submit">عرض</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
    <p class="no-data">❌ لا توجد بيانات لعرضها.</p>
@endif

@endsection
