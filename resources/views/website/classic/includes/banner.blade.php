@php
    use App\Models\Config;
    use App\Models\Page;

    $config = Config::get();

    $themeId = $config
        ->firstWhere('config_key', 'default_theme')
        ?->config_value;

    $primaryImage = $config
        ->firstWhere('config_key', 'primary_image')
        ?->config_value;

    $page = Page::where('theme_id', $themeId)
        ->where('slug', 'home')
        ->where('status', 1)
        ->first();
@endphp

@if ($page && !empty($page->body))

    @php
        $body = str_replace(
            '{{primary_image}}',
            asset($primaryImage),
            $page->body
        );
    @endphp

    @foreach (
        preg_split(
            '/(<[^>]*>)/',
            $body,
            -1,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
        ) as $part
    )
        @if (strpos($part, '<') === 0)
            {!! __($part) !!}
        @else
            {{ __($part) }}
        @endif
    @endforeach

@endif

@section('custom-js')
@endsection