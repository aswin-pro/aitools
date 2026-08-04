@php
// Settings
use App\Models\Config;
use App\Models\Page;

$config = Config::get();
$page = Page::where('theme_id', '330599619570398')->where('slug', 'hero')->where('status', 1)->get();
@endphp

{{-- Hero section --}}
@if (!empty($page[0]->body))
    @foreach (preg_split('/(<[^>]*>)/', $page[0]->body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
        @if (strpos($part, '<') === 0)
            {!! __($part) !!}
        @else
            {{ __($part) }}
        @endif
    @endforeach
@endif