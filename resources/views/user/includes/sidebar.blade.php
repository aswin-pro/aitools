@php
    // Get plan details
    use App\Models\User;
    use App\Models\Plan;
    use Carbon\Carbon;
    use App\Models\Setting;

    // Queries
    $setting = Setting::where('status', 1)->first();

    // Fetch the user plan
    $plan = User::where('id', Auth::user()->id)
        ->where('status', 1)
        ->first();
    $plan_details = json_decode($plan->plan_details, true);

    // if ($plan_details) {
    //     // Fetch the default plan details only once if necessary
    //     if (
    //         !$plan_details ||
    //         !isset($plan_details['ai_chatgenius']) ||
    //         !isset($plan_details['ai_docsassist']) ||
    //         !isset($plan_details['ai_webchat'])
    //     ) {
    //         $planDefaults = Plan::where('id', $plan->plan_id)->first();
    //     }

    //     // Check and assign missing plan details
    //     $plan_details['ai_chatgenius'] = $plan_details['ai_chatgenius'] ?? $planDefaults->ai_chatgenius;
    //     $plan_details['ai_docsassist'] = $plan_details['ai_docsassist'] ?? $planDefaults->ai_docsassist;
    //     $plan_details['ai_webchat'] = $plan_details['ai_webchat'] ?? $planDefaults->ai_webchat;

    //     // Update plan details if necessary
    //     if ($plan_details !== json_decode($plan->plan_details, true)) {
    //         $plan->plan_details = json_encode($plan_details);
    //         $plan->updated_at = Carbon::now();
    //         $plan->save();
    //     }

    //     // Fetch the updated plan details
    //     $plan_details = json_decode($plan->plan_details, true);
    // }
@endphp

<aside class="navbar navbar-vertical navbar-expand-lg d-print-none bg-body-tertiary">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-brand navbar-brand-autodark">
            <a href="{{ route('user.dashboard') }}" class="navbar-brand">
                <img src="{{ asset($setting->site_logo) }}" width="200" height="50" alt="{{ config('app.name') }}"
                    class="navbar-brand-image logo-light">

                <img src="{{ asset($setting->site_logo_light) }}" width="200" height="50"
                    alt="{{ config('app.name') }}" class="navbar-brand-image logo-dark">
            </a>
        </div>
        <div class="navbar-nav flex-row d-lg-none">
            {{-- Languages --}}
            @if (count(config('app.languages')) > 1)
                <div class="nav-item dropdown mx-2">
                    <div class="lang">
                        <select class="form-select small-btn" placeholder="{{ __('Select a language') }}"
                            id="selectLang">
                            @foreach (config('app.languages') as $langLocale => $langName)
                                <option value="{{ $langLocale }}"
                                    {{ app()->getLocale() == $langLocale ? 'selected' : '' }}>
                                    <strong>{{ $langName }}</strong>
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    <span class="img-rounded">
                        <img src="{{ Auth::user()->profile_image == null ? asset('images/profile.png') : asset(Auth::user()->profile_image) }}"
                            alt="{{ Auth::user()->name }}">
                    </span>
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="mt-1 small text-muted">
                            {{ __('Customer') }}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="{{ route('user.index.account') }}"
                        class="dropdown-item">{{ __('Profile & account') }}</a>
                    {{-- Light / Dark Mode --}}
                    <a href="{{ route('user.change.theme', 'dark') }}" class="dropdown-item hide-theme-dark"
                        data-bs-placement="bottom">
                        {{ __('Dark mode') }}
                    </a>
                    <a href="{{ route('user.change.theme', 'light') }}" class="dropdown-item hide-theme-light"
                        data-bs-placement="bottom">
                        {{ __('Light mode') }}
                    </a>
                    <a href="{{ route('logout') }}" class="dropdown-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                    <form class="logout" id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav m-0 ml-lg-auto p-3 p-lg-0 overflow-y-auto bg-body-tertiary"
                style="z-index: 9999999 !important;">
                <li class="d-inline d-lg-none">
                    <button class="navbar-toggler float-right" type="button" data-bs-toggle="collapse"
                        data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </li>

                {{-- Dashboard --}}
                <li class="nav-item {{ request()->is('user/dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('user.dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <polyline points="5 12 3 12 12 3 21 12 19 12" />
                                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Dashboard') }}
                        </span>
                    </a>
                </li>

                {{-- AI Content --}}
                <li
                    class="nav-item {{ request()->is('user/ai/gc') || request()->is('user/ai/gc/templates') || request()->is('user/ai/gc/new*') || request()->is('user/ai/gc/edit/*') || request()->is('user/ai/gc/view/*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('user.all.ai.content') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-file-text-ai">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M10 21h-3a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v3.5" />
                                <path d="M9 9h1" />
                                <path d="M9 13h2.5" />
                                <path d="M9 17h1" />
                                <path d="M14 21v-4a2 2 0 1 1 4 0v4" />
                                <path d="M14 19h4" />
                                <path d="M21 15v6" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('AI Content') }}
                        </span>
                    </a>
                </li>

                {{-- AI Images --}}
                <li class="nav-item {{ request()->is('user/ai/gi*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('user.all.ai.images') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-photo">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M15 8h.01" />
                                <path
                                    d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" />
                                <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" />
                                <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('AI Images') }}
                        </span>
                    </a>
                </li>

                @if (isset($plan_details))
                    @if ($plan_details['ai_code'] == 1)
                        {{-- AI Code --}}
                        <li class="nav-item {{ request()->is('user/ai/gcode*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('user.all.ai.code') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-code">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M7 8l-4 4l4 4" />
                                        <path d="M17 8l4 4l-4 4" />
                                        <path d="M14 4l-4 16" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('AI Code') }}
                                </span>
                            </a>
                        </li>
                    @endif

                    @if ($plan_details['ai_chatgenius'] == 1)
                        {{-- AI Chat --}}
                        <li class="nav-item {{ request()->is('user/ai/chatgenius*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('user.all.ai.chatgenius') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-message">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 9h8" />
                                        <path d="M8 13h6" />
                                        <path
                                            d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('AI Chat') }}
                                </span>
                            </a>
                        </li>
                    @endif

                    @if ($plan_details['ai_docsassist'] == 1)
                        {{-- AI Docs --}}
                        <li class="nav-item {{ request()->is('user/ai/docu-assistant*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('user.all.ai.docuassistant') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-doc">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
                                        <path d="M5 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z" />
                                        <path d="M20 16.5a1.5 1.5 0 0 0 -3 0v3a1.5 1.5 0 0 0 3 0" />
                                        <path
                                            d="M12.5 15a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1 -3 0v-3a1.5 1.5 0 0 1 1.5 -1.5z" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('AI Docs') }}
                                </span>
                            </a>
                        </li>
                    @endif

                    @if ($plan_details['ai_webchat'] == 1)
                        {{-- AI Web Chat --}}
                        <li class="nav-item {{ request()->is('user/ai/webchat*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('user.all.ai.webchat') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-world-search">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M21 12a9 9 0 1 0 -9 9" />
                                        <path d="M3.6 9h16.8" />
                                        <path d="M3.6 15h7.9" />
                                        <path d="M11.5 3a17 17 0 0 0 0 18" />
                                        <path d="M12.5 3a16.984 16.984 0 0 1 2.574 8.62" />
                                        <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                        <path d="M20.2 20.2l1.8 1.8" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('AI Web Chat') }}
                                </span>
                            </a>
                        </li>
                    @endif

                    @if ($plan_details['ai_speech_to_text'] == 1)
                        {{-- AI Speech to Text --}}
                        <li class="nav-item {{ request()->is('user/ai/gst*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('user.all.ai.speech.to.text') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-brand-adobe-illustrator">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M3 12c0 -4.243 0 -6.364 1.318 -7.682s3.44 -1.318 7.682 -1.318s6.364 0 7.682 1.318s1.318 3.44 1.318 7.682s0 6.364 -1.318 7.682s-3.44 1.318 -7.682 1.318s-6.364 0 -7.682 -1.318s-1.318 -3.44 -1.318 -7.682" />
                                        <path
                                            d="M12.947 15.79l-.82 -2.653m-4.864 2.652l.82 -2.652m0 0l.687 -2.218c.558 -1.806 .838 -2.708 1.335 -2.708c.498 0 .777 .902 1.336 2.708l.686 2.218m-4.043 0h4.043" />
                                        <path d="M15.789 15.789v-4.736" />
                                        <path d="M15.789 8.684v-.473" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('AI Speech to Text') }}
                                </span>
                            </a>
                        </li>
                    @endif

                    @if ($plan_details['ai_text_to_speech'] == 1)
                        {{-- AI Text to Speech --}}
                        <li
                            class="nav-item {{ request()->is('user/ai/gts*') || request()->is('user/all/ai/text/to/speech') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('user.all.ai.text.to.speech') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-brand-adobe-illustrator">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M3 12c0 -4.243 0 -6.364 1.318 -7.682s3.44 -1.318 7.682 -1.318s6.364 0 7.682 1.318s1.318 3.44 1.318 7.682s0 6.364 -1.318 7.682s-3.44 1.318 -7.682 1.318s-6.364 0 -7.682 -1.318s-1.318 -3.44 -1.318 -7.682" />
                                        <path
                                            d="M12.947 15.79l-.82 -2.653m-4.864 2.652l.82 -2.652m0 0l.687 -2.218c.558 -1.806 .838 -2.708 1.335 -2.708c.498 0 .777 .902 1.336 2.708l.686 2.218m-4.043 0h4.043" />
                                        <path d="M15.789 15.789v-4.736" />
                                        <path d="M15.789 8.684v-.473" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('AI Text to Speech') }}
                                </span>
                            </a>
                        </li>
                    @endif
                @endif

                {{-- Plans --}}
                <li class="nav-item {{ request()->is('user/plans') || request()->is('user/checkout*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('user.plans') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-id"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <rect x="3" y="4" width="18" height="16" rx="3"></rect>
                                <circle cx="9" cy="10" r="2"></circle>
                                <line x1="15" y1="8" x2="17" y2="8"></line>
                                <line x1="15" y1="12" x2="17" y2="12"></line>
                                <line x1="7" y1="16" x2="17" y2="16"></line>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Plans') }}
                        </span>
                    </a>
                </li>

                {{-- Transactions --}}
                <li class="nav-item {{ request()->is('user/transactions') || request()->is('user/view-invoice*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('user.transactions') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                <rect x="9" y="3" width="6" height="4" rx="2" />
                                <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                <path d="M12 17v1m0 -8v1" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Transactions') }}
                        </span>
                    </a>
                </li>

                @if (isset($plan_details))
                    @if ($plan_details['additional_tools'] == 1)
                        {{-- Additional Tools --}}
                        <li class="nav-item dropdown {{ request()->is('user/tools*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-tools"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M3 21h4l13 -13a1.5 1.5 0 0 0 -4 -4l-13 13v4"></path>
                                        <line x1="14.5" y1="5.5" x2="18.5" y2="9.5">
                                        </line>
                                        <polyline points="12 8 7 3 3 7 8 12"></polyline>
                                        <line x1="7" y1="8" x2="5.5" y2="9.5">
                                        </line>
                                        <polyline points="16 12 21 17 17 21 12 16"></polyline>
                                        <line x1="16" y1="17" x2="14.5" y2="18.5">
                                        </line>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('Addtional Tools') }}
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('user.whois-lookup') }}">
                                    {{ __('Whois Lookup') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('user.dns-lookup') }}">
                                    {{ __('DNS Lookup') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('user.ip-lookup') }}">
                                    {{ __('IP Lookup') }}
                                </a>
                            </div>
                        </li>
                    @endif
                @endif

                {{-- My Account --}}
                <li
                    class="nav-item {{ request()->is('user/account') || request()->is('user/change-password') || request()->is('user/edit-account') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('user.index.account') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('My Account') }}
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
