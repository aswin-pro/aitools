@php
// Settings
use App\Models\Config;
use App\Models\Page;
$config = Config::get();
$page = Page::where('theme_id', $config[48]->config_value)->where('slug', 'home')->where('status', 1)->get();
@endphp

@if (!empty($page[0]->body))
    @foreach (preg_split('/(<[^>]*>)/', $page[0]->body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
        @if (strpos($part, '<') === 0)
            {!! __($part) !!}
        @else
            {{ __($part) }}
        @endif
    @endforeach
@endif

{{-- Custom JS --}}
@section('custom-js')
@endsection