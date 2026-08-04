@php
    // Settings
    use App\Models\Setting;

    $setting = Setting::where('status', 1)->first();
@endphp

{{-- Sidebar --}}
<aside class="navbar navbar-vertical navbar-expand-lg d-print-none bg-body-tertiary">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-brand navbar-brand-autodark">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
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
                            {{ Auth::user()->role_id == 4 ? __('Manager') : __('Administrator') }}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="{{ route('admin.index.account') }}"
                        class="dropdown-item">{{ __('Profile & account') }}</a>
                    {{-- Light / Dark Mode --}}
                    <a href="{{ route('admin.change.theme', 'dark') }}" class="dropdown-item hide-theme-dark"
                        data-bs-placement="bottom">
                        {{ __('Dark mode') }}
                    </a>
                    <a href="{{ route('admin.change.theme', 'light') }}" class="dropdown-item hide-theme-light"
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
                <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
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

                {{-- Templates & Categories --}}
                <li
                    class="nav-item dropdown {{ request()->is('admin/templates') || request()->is('admin/add-template') || request()->is('admin/edit-template/*') || request()->is('admin/categories') || request()->is('admin/add-category') || request()->is('admin/edit-category/*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" role="button"
                        aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-template"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M4 4m0 1a1 1 0 0 1 1 -1h14a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-14a1 1 0 0 1 -1 -1z">
                                </path>
                                <path d="M4 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z">
                                </path>
                                <path d="M14 12l6 0"></path>
                                <path d="M14 16l6 0"></path>
                                <path d="M14 20l6 0"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Templates & Categories') }}
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('admin.categories') }}">
                            {{ __('Categories') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('admin.templates') }}">
                            {{ __('Templates') }}
                        </a>
                    </div>
                </li>

                {{-- Chat Genius --}}
                <li
                    class="nav-item dropdown {{ request()->is('admin/chatgenius') || request()->is('admin/create-chatgenius') || request()->is('admin/edit-chatgenius/*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown"
                        role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-messages">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10" />
                                <path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Chat Assistants') }}
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('admin.chatgenius') }}">
                            {{ __('All Chat Assistants') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('admin.create.chatgenius') }}">
                            {{ __('Create New') }}
                        </a>
                    </div>
                </li>

                {{-- Plans --}}
                <li
                    class="nav-item dropdown {{ request()->is('admin/plans') || request()->is('admin/add-plan') || request()->is('admin/edit-plan/*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown"
                        role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-businessplan"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <ellipse cx="16" cy="6" rx="5" ry="3"></ellipse>
                                <path d="M11 6v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4"></path>
                                <path d="M11 10v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4"></path>
                                <path d="M11 14v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4"></path>
                                <path d="M7 9h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5"></path>
                                <path d="M5 15v1m0 -8v1"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Plans') }}
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('admin.index.plans') }}">
                            {{ __('All Plans') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('admin.add.plan') }}">
                            {{ __('Create New') }}
                        </a>
                    </div>
                </li>

                {{-- Customers --}}
                <li class="nav-item {{ request()->is('admin/users') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.users') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Users') }}
                        </span>
                    </a>
                </li>

                {{-- Payment Methods --}}
                <li
                    class="nav-item {{ request()->is('admin/payment-methods') || request()->is('admin/edit-payment-method/*') || request()->is('admin/configure-payment-method/*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.payment.methods') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-building-bank"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <line x1="3" y1="21" x2="21" y2="21"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                <polyline points="5 6 12 3 19 6"></polyline>
                                <line x1="4" y1="10" x2="4" y2="21"></line>
                                <line x1="20" y1="10" x2="20" y2="21"></line>
                                <line x1="8" y1="14" x2="8" y2="17"></line>
                                <line x1="12" y1="14" x2="12" y2="17"></line>
                                <line x1="16" y1="14" x2="16" y2="17"></line>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Payment Methods') }}
                        </span>
                    </a>
                </li>

                {{-- Transactions --}}
                <li
                    class="nav-item dropdown {{ request()->is('admin/transactions') || request()->is('admin/offline-transactions') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown"
                        role="button" aria-expanded="false">
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
                    <div class="dropdown-menu">
                        <a href="{{ route('admin.transactions') }}"
                            class="dropdown-item">{{ __('Transactions') }}</a>
                        <a href="{{ route('admin.offline.transactions') }}"
                            class="dropdown-item">{{ __('Offline Transactions') }}</a>
                    </div>
                </li>

                {{-- Blogs --}}
                <li
                    class="nav-item dropdown {{ request()->is('admin/blogs') || request()->is('admin/blog-categories') || request()->is('admin/create-blog') || request()->is('admin/publish-blog') || request()->is('admin/edit-blog/*') || request()->is('admin/action-blog/*') || request()->is('admin/action-blog') || request()->is('admin/create-blog-category') || request()->is('admin/edit-blog-category/*') || request()->is('admin/action-blog-category/*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown"
                        role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-article">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                <path d="M7 8h10" />
                                <path d="M7 12h10" />
                                <path d="M7 16h10" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Blogs') }}
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('admin.blogs') }}" class="dropdown-item">{{ __('Blogs') }}</a>
                        <a href="{{ route('admin.blog.categories') }}"
                            class="dropdown-item">{{ __('Categories') }}</a>
                    </div>
                </li>

                {{-- Pages --}}
                <li
                    class="nav-item {{ request()->is('admin/pages') || request()->is('admin/page/*') || request()->is('admin/add-page') || request()->is('admin/custom-page/*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.pages') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M19 18v1a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-1" />
                                <path d="M3 14h3m4.5 0h3m4.5 0h3" />
                                <path d="M5 10v-5a2 2 0 0 1 2 -2h7l5 5v2" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Pages') }}
                        </span>
                    </a>
                </li>

                {{-- Translations --}}
                <li class="nav-item {{ request()->is('languages') || request()->is('languages/*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ asset('languages') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-language">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 5h7" />
                                <path d="M9 3v2c0 4.418 -2.239 8 -5 8" />
                                <path d="M5 9c0 2.144 2.952 3.908 6.7 4" />
                                <path d="M12 20l4 -9l4 9" />
                                <path d="M19.1 18h-6.2" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Translations') }}
                        </span>
                    </a>
                </li>

                {{-- Plugins --}}
                <li
                    class="nav-item {{ request()->is('admin/plugins') || request()->is('admin/plugin*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.plugins.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-apps">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                <path
                                    d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                <path
                                    d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                <path d="M14 7l6 0" />
                                <path d="M17 4l0 6" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Plugins') }}
                        </span>
                    </a>
                </li>

                {{-- Settings --}}
                <li
                    class="nav-item dropdown {{ request()->is('admin/settings') ||
                    request()->is('admin/cron/*') ||
                    request()->is('admin/sitemap') ||
                    request()->is('admin/tax-setting') ||
                    request()->is('admin/logs') ||
                    request()->is('admin/clear/cache')
                        ? 'active'
                        : '' }}">
                    <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown"
                        data-bs-auto-close="outside" role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z">
                                </path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Settings') }}
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                {{-- General Settings --}}
                                <a href="{{ route('admin.settings') }}"
                                    class="dropdown-item">{{ __('General Settings') }}</a>

                                {{-- Cron Jobs --}}
                                <a href="{{ route('admin.cron.jobs') }}"
                                    class="dropdown-item">{{ __('Cron Jobs') }}</a>

                                {{-- Sitemap --}}
                                <a href="{{ route('admin.sitemap') }}"
                                    class="dropdown-item">{{ __('Generate Sitemap') }}</a>

                                {{-- Invoice & Tax --}}
                                <a href="{{ route('admin.tax.setting') }}"
                                    class="dropdown-item">{{ __('Invoice & Tax') }}</a>

                                <a href="{{ route('admin.logs') }}" class="dropdown-item">{{ __('Login Logs') }}</a>

                                {{-- Clear Cache --}}
                                <a href="{{ route('admin.clear.cache') }}"
                                    class="dropdown-item">{{ __('Clear Cache') }}</a>
                            </div>
                        </div>
                    </div>
                </li>

                {{-- Currencies --}}
                <li
                    class="nav-item {{ request()->is('admin/currencies') || request()->is('admin/edit-currency/*') || request()->is('admin/update-currency') || request()->is('admin/update-currency-status') || request()->is('admin/delete-currency') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.currencies') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-settings-dollar">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M13.038 20.666c-.902 .665 -2.393 .337 -2.713 -.983a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 .402 2.248" />
                                <path d="M15 12a3 3 0 1 0 -1.724 2.716" />
                                <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                <path d="M19 21v1m0 -8v1" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Currencies') }}
                        </span>
                    </a>
                </li>

                {{-- Backup --}}
                <li
                    class="nav-item {{ request()->is('admin/backups') || request()->is('admin/backups/*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.backups') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-file-zip">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6 20.735a2 2 0 0 1 -1 -1.735v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-1" />
                                <path d="M11 17a2 2 0 0 1 2 2v2a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-2a2 2 0 0 1 2 -2z" />
                                <path d="M11 5l-1 0" />
                                <path d="M13 7l-1 0" />
                                <path d="M11 9l-1 0" />
                                <path d="M13 11l-1 0" />
                                <path d="M11 13l-1 0" />
                                <path d="M13 15l-1 0" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Backups') }}
                        </span>
                    </a>
                </li>

                {{-- Software Update --}}
                <li class="nav-item {{ request()->is('admin/check') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.check') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-settings-up">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M12.501 20.93c-.866 .25 -1.914 -.166 -2.176 -1.247a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.074 .26 1.49 1.296 1.252 2.158" />
                                <path d="M19 22v-6" />
                                <path d="M22 19l-3 -3l-3 3" />
                                <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            {{ __('Software Update') }}
                        </span>
                    </a>
                </li>

                {{-- My Account --}}
                <li
                    class="nav-item {{ request()->is('admin/account') || request()->is('admin/change-password') || request()->is('admin/edit-account') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.index.account') }}">
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
