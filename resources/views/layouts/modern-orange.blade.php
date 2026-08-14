<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" co ntent="{{ csrf_token() }}">

    @isset($title)
        <title>{{ __($title) }}</title>
    @endisset

    <!-- Site Description -->
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}

    @if (isset($setting))
        <!-- Favicon -->
        <link rel="icon" href="{{ asset($setting->favicon) }}" sizes="96x96"  />
    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,400;1,500;1,600;1,700&family=Sora:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('themes/modern-orange/css/tailwind/tailwind.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/modern-orange/css/main.css') }}">

    {{-- JS --}}
    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('themes/modern-orange/js/main.js') }}"></script>

    <!-- Google Recaptcha -->

    <!-- Custom Styles -->
    @yield('custom-css')

    <!-- Google Analytics -->
    @if (isset($setting))
        @if ($setting->analytics_id != '')
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $setting->analytics_id }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());

                gtag('config', '{{ $setting->analytics_id }}');
            </script>
        @endif

        @if ($setting->google_tag != '')
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{{ $setting->google_tag }}');</script>
            <!-- End Google Tag Manager -->
        @endif
    @endif
</head>

<body class="antialiased bg-body text-body font-body zoom" dir="{{(App::isLocale('ar') || App::isLocale('ur') || App::isLocale('he') || App::isLocale('fa') ? 'rtl' : 'ltr')}}">
    
    @if (isset($setting))
        @if ($setting->google_tag != '')
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $setting->google_tag }}"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
        @endif
    @endif
    
    <div class="">
        @yield('content')

        {{-- WhatsApp Chatbot --}}
        @if ($config[49]->config_value == "1")
            <a href="https://api.whatsapp.com/send?phone={{ $config[50]->config_value }}&text={{ urlencode($config[51]->config_value) }}" class="whatapp-chatbot" target="_blank">
                <i class="fab fa-whatsapp whatapp-chatbot-icon"></i>
            </a>
        @endif
        
        {{-- Cookie consent --}}
        @include('cookie-consent::index')
    </div>

    <!-- Custom JS -->
    @yield('custom-js')

    <!-- Tawk Chat -->
    @if (isset($setting))
    @if ($setting->tawk_chat_key != "")
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        "use strict";
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/{{ $setting->tawk_chat_key }}';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->
    @endif

    @if ($setting->google_tag != "")
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $setting->google_tag }}" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif
    @endif
</body>

</html>
