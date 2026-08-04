@extends('admin.layouts.app')

{{-- Custom CSS --}}
@section('custom-css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.0.1/tinymce.min.js"
        integrity="sha512-KGtsnWohFUg0oksKq7p7eDgA1Rw2nBfqhGJn463/rGhtUY825dBqGexj8eP04LwfnsSW6dNAHAlOqKJKquHsnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <!-- Page title -->
            <div class="page-header d-print-none">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="page-pretitle">
                            {{ __('Overview') }}
                        </div>
                        <h2 class="page-title mb-2">
                            {{ __('Settings') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-fluid">

                {{-- Failed --}}
                @if (Session::has('failed'))
                    <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('failed') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                {{-- Success --}}
                @if (Session::has('success'))
                    <div class="alert alert-important alert-success alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('success') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                {{-- Settings --}}
                <div class="card">
                    <div class="card-body">
                        <div class="accordion" id="accordion-example">
                            {{-- General Configuration Settings --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-1" aria-expanded="false">
                                        <h2>{{ __('General Configuration Settings') }}</h2>
                                    </button>
                                </h2>
                                <div id="collapse-1" class="accordion-collapse collapse"
                                    data-bs-parent="#accordion-example">
                                    <div class="accordion-body pt-0">
                                        <form action="{{ route('admin.change.general.settings') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                {{-- Show Website Frontend? --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="show_website">{{ __('Show Website Frontend?') }}</label>
                                                        <select name="show_website" id="show_website"
                                                            class="form-control form-select" required>
                                                            <option value="yes"
                                                                {{ $config[43]->config_value == 'yes' ? ' selected' : '' }}>
                                                                {{ __('Yes') }}</option>
                                                            <option value="no"
                                                                {{ $config[43]->config_value == 'no' ? ' selected' : '' }}>
                                                                {{ __('No') }}</option>
                                                        </select>
                                                        <small><strong><span class="text-danger">{{ __('Note') }}</span>
                                                                :
                                                                {{ __('If there is no website frontend, the website will automatically go to the login page.') }}</strong></small>
                                                    </div>
                                                </div>

                                                {{-- Timezone --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="timezone">{{ __('Timezone') }}</label>
                                                        <select name="timezone" id="timezone"
                                                            class="form-control form-select" required>
                                                            @foreach (timezone_identifiers_list() as $timezone)
                                                                <option value="{{ $timezone }}"
                                                                    {{ $config[2]->config_value == $timezone ? ' selected' : '' }}>
                                                                    {{ $timezone }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Currency --}}
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="currency">{{ __('Currency') }}</label>
                                                        <select class="tomselected form-select" name="currency"
                                                            id="currency" required>
                                                            @foreach ($currencies as $currency)
                                                                <option value="{{ $currency->iso_code }}"
                                                                    {{ $config[1]->config_value == $currency->iso_code ? ' selected' : '' }}>
                                                                    {{ $currency->name }} ({{ $currency->symbol }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Currency --}}
                                                <div class="col-md-4 col-xl-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="currency">{{ __('Currency') }}</label>
                                                        <select name="currency" id="currency" class="form-control"
                                                            required>
                                                            @foreach ($currencies as $currency)
                                                                <option value="{{ $currency->iso_code }}"
                                                                    {{ $config[1]->config_value == $currency->iso_code ? ' selected' : '' }}>
                                                                    {{ $currency->name }} ({{ $currency->symbol }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Currency format type --}}
                                                <div class=" col-xl-4 col-12">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="currency_format">{{ __('Currency Format') }}</label>
                                                        <select name="currency_format" id="currency_format"
                                                            class="form-select" required>
                                                            <option value="1,234,567.89"
                                                                {{ $config[62]->config_value == '1,234,567.89' ? 'selected' : '' }}>
                                                                {{ __('1,234,567.89') }}</option>
                                                            <option value="12,34,567.89"
                                                                {{ $config[62]->config_value == '12,34,567.89' ? 'selected' : '' }}>
                                                                {{ __('12,34,567.89') }}</option>
                                                            <option value="1.234.567,89"
                                                                {{ $config[62]->config_value == '1.234.567,89' ? 'selected' : '' }}>
                                                                {{ __('1.234.567,89') }}</option>
                                                            <option value="1 234 567,89"
                                                                {{ $config[62]->config_value == '1 234 567,89' ? 'selected' : '' }}>
                                                                {{ __('1 234 567,89') }}</option>
                                                            <option value="1'234'567.89"
                                                                {{ $config[62]->config_value == "1'234'567.89" ? 'selected' : '' }}>
                                                                {{ __("1'234'567.89") }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Currency Decimals Places --}}
                                                <div class="col-xl-4 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="currency_decimals_place">{{ __('Decimals Places') }}</label>
                                                        <input type="number" class="form-control reduce-control"
                                                            name="currency_decimals_place" id="currency_decimals_place"
                                                            value="{{ $config[63]->config_value }}"
                                                            placeholder="{{ __('Decimals Places') }}" min="0"
                                                            step="1" max="3" required>
                                                        <small
                                                            class="text-muted">{{ __('If you don\'t need decimal vale, set 0') }}</small>
                                                    </div>
                                                </div>

                                                {{-- Date Time Format --}}
                                                <div class="col-xl-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="date_time_format">{{ __('Date Time Format') }}</label>
                                                        <select name="date_time_format" id="date_time_format"
                                                            class="form-select" required>
                                                            @php
                                                                $availableDateTimeFormats = getDateTimeFormats();
                                                            @endphp
                                                            @foreach ($availableDateTimeFormats as $key => $value)
                                                                <option value="{{ $key }}"
                                                                    {{ $config[61]->config_value == $key ? 'selected' : '' }}>
                                                                    {{ $value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Default Language --}}
                                                <div class="col-xl-6 col-12">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="default_language">{{ __('Default Language') }}</label>
                                                        <select name="default_language" id="default_language"
                                                            class="form-select" required>
                                                            @php
                                                                $availableLanguages = [
                                                                    'en' => __('English'),
                                                                    'ar' => __('Arabic'),
                                                                    'bn' => __('Bangla'),
                                                                    'bg' => __('Bulgarian'),
                                                                    'zh' => __('Chinese'),
                                                                    'nl' => __('Dutch'),
                                                                    'fr' => __('French'),
                                                                    'de' => __('German'),
                                                                    'hi' => __('Hindi'),
                                                                    'he' => __('Hebrew'),
                                                                    'hu' => __('Hungarian'),
                                                                    'id' => __('Indonesian'),
                                                                    'it' => __('Italian'),
                                                                    'ja' => __('Japanese'),
                                                                    'lt' => __('Lithuanian'),
                                                                    'ms' => __('Malay'),
                                                                    'pt' => __('Portuguese'),
                                                                    'pl' => __('Polish'),
                                                                    'ro' => __('Romanian'),
                                                                    'ru' => __('Russian'),
                                                                    'es' => __('Spanish'),
                                                                    'si' => __('Sinhala'),
                                                                    'sv' => __('Swedish'),
                                                                    'ta' => __('Tamil'),
                                                                    'th' => __('Thai'),
                                                                    'tr' => __('Turkish'),
                                                                    'ur' => __('Urdu'),
                                                                    'vi' => __('Vietnamese'),
                                                                ];
                                                            @endphp

                                                            @foreach ($availableLanguages as $code => $name)
                                                                <option value="{{ $code }}"
                                                                    {{ $defaultLanguage == $code ? 'selected' : '' }}>
                                                                    {{ $name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Languages --}}
                                                <div class="col-xl-12 col-12">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="language">{{ __('Languages') }}</label>
                                                        <select name="languages[]" id="languages" class="form-select"
                                                            required multiple>
                                                            @php
                                                                $availableLanguages = [
                                                                    'en' => __('English'),
                                                                    'ar' => __('Arabic'),
                                                                    'bn' => __('Bangla'),
                                                                    'bg' => __('Bulgarian'),
                                                                    'zh' => __('Chinese'),
                                                                    'nl' => __('Dutch'),
                                                                    'fr' => __('French'),
                                                                    'de' => __('German'),
                                                                    'hi' => __('Hindi'),
                                                                    'he' => __('Hebrew'),
                                                                    'hu' => __('Hungarian'),
                                                                    'id' => __('Indonesian'),
                                                                    'it' => __('Italian'),
                                                                    'ja' => __('Japanese'),
                                                                    'lt' => __('Lithuanian'),
                                                                    'ms' => __('Malay'),
                                                                    'pt' => __('Portuguese'),
                                                                    'pl' => __('Polish'),
                                                                    'ro' => __('Romanian'),
                                                                    'ru' => __('Russian'),
                                                                    'es' => __('Spanish'),
                                                                    'si' => __('Sinhala'),
                                                                    'sv' => __('Swedish'),
                                                                    'ta' => __('Tamil'),
                                                                    'th' => __('Thai'),
                                                                    'tr' => __('Turkish'),
                                                                    'ur' => __('Urdu'),
                                                                    'vi' => __('Vietnamese'),
                                                                ];
                                                            @endphp

                                                            @foreach ($availableLanguages as $code => $name)
                                                                <option value="{{ $code }}"
                                                                    @if (in_array($code, $selectedLanguages ?? [])) selected @endif>
                                                                    {{ $name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Default Plan Term Settings --}}
                                            <div class="row">
                                                <div class="col-md-4 col-xl-4">
                                                    <h2 class="page-title my-3">
                                                        {{ __('Default Plan Term Settings') }}
                                                    </h2>
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="term">{{ __('Default Plan Term') }}</label>
                                                        <select name="term" id="term"
                                                            class="form-control form-select" required>
                                                            <option value="monthly"
                                                                {{ $config[8]->config_value == 'monthly' ? 'selected' : '' }}>
                                                                {{ __('Monthly') }}</option>
                                                            <option value="yearly"
                                                                {{ $config[8]->config_value == 'yearly' ? 'selected' : '' }}>
                                                                {{ __('Yearly') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Default Image Upload Limit --}}
                                                <div class="col-md-4 col-xl-4 mb-2">
                                                    <h2 class="page-title my-3">
                                                        {{ __('Default Image Upload Limit') }}
                                                    </h2>
                                                    <div class="mb-3">
                                                        <label class="form-label" for="image_limit">{{ __('Size') }}
                                                        </label>
                                                        <input type="number" class="form-control" name="image_limit"
                                                            value="{{ $settings->image_limit['SIZE_LIMIT'] }}"
                                                            placeholder="{{ __('Size') }}" min="1024">
                                                    </div>
                                                </div>

                                                {{-- Update button --}}
                                                <div class="text-end">
                                                    <div class="d-flex">
                                                        <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                            {{ __('Update') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Website Configuration Settings --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-2" aria-expanded="false">
                                        <h2>{{ __('Website Configuration Settings') }}</h2>
                                    </button>
                                </h2>
                                <div id="collapse-2" class="accordion-collapse collapse"
                                    data-bs-parent="#accordion-example">
                                    <div class="accordion-body pt-0">
                                        <form action="{{ route('admin.change.website.settings') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">

                                                {{-- Themes --}}
                                                <div class="col-md-12 col-xl-12">
                                                    <div class="mb-3">
                                                        <label class="form-label required">{{ __('Themes') }}</label>
                                                        <div class="row g-2">
                                                            {{-- Themes --}}
                                                            @foreach ($themes as $theme)
                                                                <div class="col-4 col-sm-4">
                                                                    <label class="form-imagecheck mb-2">
                                                                        <input name="theme_id" type="radio"
                                                                            value="{{ $theme->theme_id }}"
                                                                            class="form-imagecheck-input"
                                                                            {{ $config[48]->config_value == $theme->theme_id ? 'checked' : '' }} />
                                                                        <span class="form-imagecheck-figure">
                                                                            <img src="{{ asset($theme->cover_image) }}"
                                                                                alt="{{ $theme->theme_name }}"
                                                                                class="form-imagecheck-image">
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Theme Colors --}}
                                                <div class="col-md-12 col-xl-12">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label required">{{ __('Theme Colors') }}</label>
                                                        <div class="row g-2">
                                                            <div class="col-auto">
                                                                <label class="form-colorinput">
                                                                    <input name="app_theme" type="radio" value="blue"
                                                                        class="form-colorinput-input"
                                                                        {{ $config[11]->config_value == 'blue' ? 'checked' : '' }} />
                                                                    <span class="form-colorinput-color bg-blue"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-auto">
                                                                <label class="form-colorinput form-colorinput-light">
                                                                    <input name="app_theme" type="radio" value="indigo"
                                                                        class="form-colorinput-input"
                                                                        {{ $config[11]->config_value == 'indigo' ? 'checked' : '' }} />
                                                                    <span class="form-colorinput-color bg-indigo"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-auto">
                                                                <label class="form-colorinput">
                                                                    <input name="app_theme" type="radio" value="green"
                                                                        class="form-colorinput-input"
                                                                        {{ $config[11]->config_value == 'green' ? 'checked' : '' }} />
                                                                    <span class="form-colorinput-color bg-green"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-auto">
                                                                <label class="form-colorinput">
                                                                    <input name="app_theme" type="radio" value="yellow"
                                                                        class="form-colorinput-input"
                                                                        {{ $config[11]->config_value == 'yellow' ? 'checked' : '' }} />
                                                                    <span class="form-colorinput-color bg-yellow"></span>
                                                                </label>
                                                            </div>

                                                            <div class="col-auto">
                                                                <label class="form-colorinput">
                                                                    <input name="app_theme" type="radio" value="red"
                                                                        class="form-colorinput-input"
                                                                        {{ $config[11]->config_value == 'red' ? 'checked' : '' }} />
                                                                    <span class="form-colorinput-color bg-red"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-auto">
                                                                <label class="form-colorinput">
                                                                    <input name="app_theme" type="radio" value="purple"
                                                                        class="form-colorinput-input"
                                                                        {{ $config[11]->config_value == 'purple' ? 'checked' : '' }} />
                                                                    <span class="form-colorinput-color bg-purple"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-auto">
                                                                <label class="form-colorinput">
                                                                    <input name="app_theme" type="radio" value="pink"
                                                                        class="form-colorinput-input"
                                                                        {{ $config[11]->config_value == 'pink' ? 'checked' : '' }} />
                                                                    <span class="form-colorinput-color bg-pink"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-auto">
                                                                <label class="form-colorinput form-colorinput-light">
                                                                    <input name="app_theme" type="radio" value="gray"
                                                                        class="form-colorinput-input"
                                                                        {{ $config[11]->config_value == 'gray' ? 'checked' : '' }} />
                                                                    <span class="form-colorinput-color bg-muted"></span>
                                                                </label>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Home Banner Image --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Banner Image') }} <span
                                                                class="text-muted">
                                                                ({{ __('Recommended size : 1000 x 667') }})</span></div>
                                                        <input type="file" class="form-control" name="primary_image"
                                                            placeholder="{{ __('Banner Image') }}"
                                                            accept=".png,.jpg,.jpeg,.gif,.svg" />
                                                    </div>
                                                </div>

                                                {{-- Website Logo (Dark) --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Website Logo (Dark)') }} <span
                                                                class="text-muted">
                                                                ({{ __('Recommended size : 200 x 90') }})</span></div>
                                                        <input type="file" class="form-control" name="site_logo"
                                                            placeholder="{{ __('Website Logo') }}"
                                                            accept=".png,.jpg,.jpeg,.gif,.svg" />
                                                    </div>
                                                </div>

                                                {{-- Website Logo (Light) --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Website Logo (Light)') }} <span
                                                                class="text-muted">
                                                                ({{ __('Recommended size : 200 x 90') }})</span></div>
                                                        <input type="file" class="form-control" name="site_logo_light"
                                                            placeholder="{{ __('Website Logo (Light)') }}"
                                                            accept=".png,.jpg,.jpeg,.gif,.svg" />
                                                    </div>
                                                </div>

                                                {{-- Favicon --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Favicon') }} <span
                                                                class="text-muted">
                                                                ({{ __('Recommended size : 200 x 200') }})</span></div>
                                                        <input type="file" class="form-control" name="favi_icon"
                                                            placeholder="{{ __('Favicon') }}"
                                                            accept=".png,.jpg,.jpeg,.gif,.svg" />
                                                    </div>
                                                </div>

                                                {{-- App Name --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('App Name') }}</label>
                                                        <input type="text" class="form-control" name="app_name"
                                                            value="{{ config('app.name') }}" maxlength="120"
                                                            placeholder="{{ __('App Name') }}">
                                                    </div>
                                                </div>

                                                {{-- Site Name --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label required">{{ __('Site Name') }}</label>
                                                        <input type="text" class="form-control" name="site_name"
                                                            value="{{ $settings->site_name }}" maxlength="120"
                                                            placeholder="{{ __('Site Name') }}" required>
                                                    </div>
                                                </div>

                                                {{-- Update button --}}
                                                <div class="text-end">
                                                    <div class="d-flex">
                                                        <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                            {{ __('Update') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- AI Tools Configuration Settings --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-7">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-7" aria-expanded="false">
                                        <h2>{{ __('AI Tools Configuration Settings') }}</h2>
                                    </button>
                                </h2>
                                <div id="collapse-7" class="accordion-collapse collapse"
                                    data-bs-parent="#accordion-example">
                                    <div class="accordion-body pt-0">
                                        <form action="{{ route('admin.change.ai.settings') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">

                                                {{-- OpenAI Text Model --}}
                                                @php
                                                    $textModels = [
                                                        // GPT-5.4 Series (Latest)
                                                        'gpt-5.4' => 'GPT-5.4',
                                                        'gpt-5.4-pro' => 'GPT-5.4 Pro',
                                                        'gpt-5.4-mini' => 'GPT-5.4 Mini',
                                                        'gpt-5.4-nano' => 'GPT-5.4 Nano',

                                                        // GPT-5.3 Series
                                                        'gpt-5.3' => 'GPT-5.3',

                                                        // GPT-5.2 Series
                                                        'gpt-5.2' => 'GPT-5.2',
                                                        'gpt-5.2-pro' => 'GPT-5.2 Pro',

                                                        // GPT-5.1 Series
                                                        'gpt-5.1' => 'GPT-5.1',

                                                        // GPT-5 Series
                                                        'gpt-5' => 'GPT-5',
                                                        'gpt-5-mini' => 'GPT-5 Mini',
                                                        'gpt-5-nano' => 'GPT-5 Nano',
                                                        'gpt-5-pro' => 'GPT-5 Pro',

                                                        // o-Series Reasoning Models
                                                        'o4-mini' => 'o4 Mini',
                                                        'o3' => 'o3',
                                                        'o3-pro' => 'o3 Pro',
                                                        'o3-mini' => 'o3 Mini',
                                                        'o1' => 'o1',
                                                        'o1-mini' => 'o1 Mini',
                                                        'o1-preview' => 'o1 Preview',

                                                        // GPT-4.5
                                                        'gpt-4.5-preview' => 'GPT-4.5 Preview',

                                                        // GPT-4.1 Series
                                                        'gpt-4.1' => 'GPT-4.1',
                                                        'gpt-4.1-mini' => 'GPT-4.1 Mini',
                                                        'gpt-4.1-nano' => 'GPT-4.1 Nano',

                                                        // GPT OSS (Open Weights)
                                                        'gpt-oss-120b' => 'GPT OSS 120B',
                                                        'gpt-oss-20b' => 'GPT OSS 20B',

                                                        // GPT-4o Series
                                                        'gpt-4o' => 'GPT-4o',
                                                        'gpt-4o-mini' => 'GPT-4o Mini',

                                                        // GPT-4 Series
                                                        'gpt-4-turbo' => 'GPT-4 Turbo',
                                                        'gpt-4' => 'GPT-4',
                                                        'gpt-4-32k' => 'GPT-4 (32k)',
                                                        'gpt-4-1106-preview' => 'GPT-4 Turbo (Preview)',
                                                        'gpt-4-vision-preview' => 'GPT-4 Turbo with Vision',

                                                        // GPT-3.5 Series
                                                        'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
                                                        'gpt-3.5-turbo-16k' => 'GPT-3.5 Turbo (16k)',
                                                        'gpt-3.5-turbo-1106' => 'GPT-3.5 Turbo (Updated)',

                                                        // Legacy
                                                        'text-davinci-003' => 'Text Davinci 003',
                                                        'text-davinci-002' => 'Text Davinci 002',
                                                        'text-ada-001' => 'Text Ada 001',
                                                    ];
                                                @endphp

                                                <div class="col-md-6 col-xl-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="ai_model">{{ __('Open AI Text Model') }}</label>
                                                        <select name="ai_model" id="ai_model"
                                                            class="form-control form-select" required>
                                                            @foreach ($textModels as $key => $label)
                                                                <option value="{{ $key }}"
                                                                    {{ $config[34]->config_value == $key ? 'selected' : '' }}>
                                                                    {{ __($label) }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span>{{ __('To find out which model is right for you with OpenAI pricing,') }}
                                                            <a href="https://platform.openai.com/docs/pricing"
                                                                rel="nofollow"
                                                                target="_blank">{{ __('click here') }}</a> </span>
                                                    </div>
                                                </div>

                                                {{-- OpenAI Image Model --}}
                                                @php
                                                    $imageModels = [
                                                        // GPT Image Series
                                                        'gpt-image-1.5' => 'GPT Image 1.5', // Latest - released late 2025
                                                        'chatgpt-image-latest' => 'GPT Image (Latest)',
                                                        'gpt-image-1' => 'GPT Image 1',
                                                        'gpt-image-1-mini' => 'GPT Image 1 Mini',

                                                        // DALL·E (deprecated — support ends May 12, 2026)
                                                        'dall-e-3' => 'DALL·E 3 (Deprecated)',
                                                        'dall-e-2' => 'DALL·E 2 (Deprecated)',
                                                    ];
                                                @endphp

                                                <div class="col-md-6 col-xl-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="image_model">{{ __('Open AI Image Model') }}</label>
                                                        <select name="image_model" id="image_model"
                                                            class="form-control form-select" required>
                                                            @foreach ($imageModels as $key => $label)
                                                                <option value="{{ $key }}"
                                                                    {{ $config[46]->config_value == $key ? 'selected' : '' }}>
                                                                    {{ __($label) }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span>{{ __('To find out which model is right for you with OpenAI pricing,') }}
                                                            <a href="https://platform.openai.com/docs/pricing"
                                                                rel="nofollow"
                                                                target="_blank">{{ __('click here') }}</a> </span>
                                                    </div>
                                                </div>

                                                {{-- OpenAI Audio Model --}}
                                                @php
                                                    $audioModels = [
                                                        // ── Text-to-Speech (TTS) ──────────────────────────────────────────
                                                        'gpt-4o-mini-tts' => 'GPT-4o Mini TTS', // Latest, steerable
                                                        'gpt-4o-mini-tts-2025-12-15' => 'GPT-4o Mini TTS (Dec 2025)', // Pinned snapshot (~35% lower WER)
                                                        'tts-1' => 'TTS-1', // Low latency
                                                        'tts-1-hd' => 'TTS-1 HD', // Higher quality
                                                    ];
                                                @endphp

                                                <div class="col-md-6 col-xl-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="text_speech_model">{{ __('Open AI Text to Speech Model') }}</label>
                                                        <select name="text_speech_model" id="text_speech_model"
                                                            class="form-control form-select" required>
                                                            @foreach ($audioModels as $key => $label)
                                                                <option value="{{ $key }}"
                                                                    {{ $config[47]->config_value == $key ? 'selected' : '' }}>
                                                                    {{ __($label) }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span>{{ __('To find out which model is right for you with OpenAI pricing,') }}
                                                            <a href="https://platform.openai.com/docs/pricing"
                                                                rel="nofollow"
                                                                target="_blank">{{ __('click here') }}</a> </span>
                                                    </div>
                                                </div>

                                                {{-- OpenAI API Key --}}
                                                <div class="col-md-6 col-xl-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="openai_api_key">{{ __('OpenAI API Key') }}
                                                        </label>
                                                        <input type="text" class="form-control" name="openai_api_key"
                                                            value="{{ $config[35]->config_value }}" maxlength="500"
                                                            placeholder="{{ __('OpenAI API Key (Eg: sk-****************)') }}"
                                                            required>
                                                        <span>{{ __('If you did not get a OpenAI API Key, create a') }} <a
                                                                href="https://platform.openai.com/account/api-keys"
                                                                rel="nofollow"
                                                                target="_blank">{{ __('new API Key.') }}</a> </span>
                                                    </div>
                                                </div>

                                                {{-- Maximum Words Length --}}
                                                <div class="col-md-6 col-xl-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="word_length">{{ __('Maximum Words Length') }}
                                                        </label>
                                                        <input type="number" class="form-control" name="word_length"
                                                            min="1" value="{{ $config[30]->config_value }}"
                                                            placeholder="{{ __('Maximum Words Length (Eg: 1200)') }}"
                                                            required>
                                                    </div>
                                                </div>

                                                {{-- Maximum Images Options --}}
                                                <div class="col-md-6 col-xl-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="image_length">{{ __('Maximum Images Options') }}
                                                        </label>
                                                        <input type="number" class="form-control" name="image_length"
                                                            min="1" value="{{ $config[42]->config_value }}"
                                                            placeholder="{{ __('Maximum Images Options (Eg: 3)') }}"
                                                            required>
                                                    </div>
                                                </div>

                                                {{-- <h2 class="page-title my-4">
                                                {{ __('Tiny Cloud (Text Editor) Configuration Settings') }}
                                            </h2> --}}

                                                {{-- Tiny Cloud API Key --}}
                                                <div class="col-md-12 col-xl-12 mb-2 d-none">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="tiny_api_key">{{ __('Tiny Cloud API Key') }}
                                                        </label>
                                                        <input type="text" class="form-control" name="tiny_api_key"
                                                            value="{{ $config[36]->config_value }}" maxlength="120"
                                                            placeholder="{{ __('Tiny Cloud API Key (Eg: ytf5**************************)') }}"
                                                            required>
                                                        <span>{{ __('If you did not get a Tiny Cloud API Key, create a') }}
                                                            <a href="https://www.tiny.cloud/my-account/dashboard"
                                                                rel="nofollow"
                                                                target="_blank">{{ __('new API Key.') }}</a> </span>
                                                    </div>
                                                </div>

                                                {{-- Update button --}}
                                                <div class="text-end">
                                                    <div class="d-flex">
                                                        <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                            {{ __('Update') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- AWS S3 Configuration Settings --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-8">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-8" aria-expanded="false">
                                        <h2>{{ __('AWS S3 Configuration Settings') }}</h2>
                                    </button>
                                </h2>
                                <div id="collapse-8" class="accordion-collapse collapse"
                                    data-bs-parent="#accordion-example">
                                    <div class="accordion-body pt-0">
                                        <form action="{{ route('admin.change.aws.s3.settings') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">

                                                {{-- Enable AWS? --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="aws_enable">{{ __('AWS') }}</label>
                                                        <select name="aws_enable" id="aws_enable"
                                                            class="form-control form-select">
                                                            <option value="on"
                                                                {{ env('AWS_ENABLE') == 'on' ? ' selected' : '' }}>
                                                                {{ __('Enable') }}</option>
                                                            <option value="off"
                                                                {{ env('AWS_ENABLE') == 'off' ? ' selected' : '' }}>
                                                                {{ __('Disable') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Access Key ID --}}
                                                <div class="col-md-4 col-xl-4 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="access_key">{{ __('Access Key ID') }}
                                                        </label>
                                                        <input type="text" class="form-control" name="access_key"
                                                            id="access_key" value="{{ env('AWS_ACCESS_KEY_ID') }}"
                                                            maxlength="120"
                                                            placeholder="{{ __('Access Key ID (Eg: AKI****************)') }}">
                                                    </div>
                                                </div>

                                                {{-- Secret Access Key --}}
                                                <div class="col-md-4 col-xl-4 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="secret_key">{{ __('Secret Access Key') }}
                                                        </label>
                                                        <input type="text" class="form-control" name="secret_key"
                                                            id="secret_key" value="{{ env('AWS_SECRET_ACCESS_KEY') }}"
                                                            maxlength="120"
                                                            placeholder="{{ __('Secret Access Key (Eg: RYaA********/E****************)') }}">
                                                    </div>
                                                </div>

                                                {{-- Default Region --}}
                                                <div class="col-md-4 col-xl-4 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="default_region">{{ __('Access Region') }}
                                                        </label>
                                                        <input type="text" class="form-control" name="default_region"
                                                            id="default_region" value="{{ env('AWS_DEFAULT_REGION') }}"
                                                            maxlength="120"
                                                            placeholder="{{ __('Default Region (Eg: ap-east-1)') }}">
                                                    </div>
                                                </div>

                                                {{-- Bucket --}}
                                                <div class="col-md-4 col-xl-4 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="bucket">{{ __('Bucket') }}
                                                        </label>
                                                        <input type="text" class="form-control" name="bucket"
                                                            id="bucket" value="{{ env('AWS_BUCKET') }}"
                                                            maxlength="120"
                                                            placeholder="{{ __('Bucket (Eg: ap-east-1)') }}">
                                                    </div>
                                                </div>

                                                {{-- Use Path Style Endpoint --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="end_point">{{ __('Use Path Style Endpoint') }}</label>
                                                        <select name="end_point" id="end_point" class="form-control">
                                                            <option value="true"
                                                                {{ env('AWS_USE_PATH_STYLE_ENDPOINT') == true ? ' selected' : '' }}>
                                                                {{ __('true') }}</option>
                                                            <option value="false"
                                                                {{ env('AWS_USE_PATH_STYLE_ENDPOINT') == false ? ' selected' : '' }}>
                                                                {{ __('false') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Update button --}}
                                                <div class="text-end">
                                                    <div class="d-flex">
                                                        <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                            {{ __('Update') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('admin.includes.footer')
    </div>

    {{-- Custom JS --}}
@section('custom-js')
    <script src="{{ asset('js/tom-select.base.min.js') }}"></script>
    <script>
        function validatePort(input) {
            "use strict";

            const maxLength = 5; // Set your desired max length
            if (input.value.length > maxLength) {
                input.value = input.value.slice(0, maxLength);
            }
        }
    </script>
    <script>
        tinymce.init({
            selector: 'textarea#bank_transfer',
            plugins: 'code preview importcss searchreplace autolink autosave save directionality visualblocks visualchars link table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
            menubar: 'file edit view insert format tools table help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | pagebreak | link',
            toolbar_sticky: true,
            height: 300,
            menubar: false,
            statusbar: false,
            autosave_interval: '30s',
            autosave_prefix: '{path}{query}-{id}-',
            autosave_restore_when_empty: false,
            autosave_retention: '2m',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
        });
    </script>
    <script>
        // Array of element IDs
        var elementIds = ['currency_format', 'date_time_format', 'default_language', 'languages', 'show_website',
            'timezone', 'currency', 'term', 'cookie', 'show_whatsapp_chatbot', 'paypal_mode', 'ai_model', 'image_model',
            'text_speech_model', 'aws_enable', 'recaptcha_enable', 'google_auth_enable', 'mail_encryption',
            'disable_user_email_verification'
        ];

        // Loop through each element ID
        elementIds.forEach(function(id) {
            // Check if the element exists
            var el = document.getElementById(id);
            if (el) {
                // Apply TomSelect to the element
                new TomSelect(el, {
                    copyClassesToDropdown: false,
                    dropdownClass: 'dropdown-menu ts-dropdown',
                    optionClass: 'dropdown-item',
                    controlInput: '<input>',
                    render: {
                        item: function(data, escape) {
                            if (data.customProperties) {
                                return '<div><span class="dropdown-item-indicator">' + data
                                    .customProperties + '</span>' + escape(data.text) + '</div>';
                            }
                            return '<div>' + escape(data.text) + '</div>';
                        },
                        option: function(data, escape) {
                            if (data.customProperties) {
                                return '<div><span class="dropdown-item-indicator">' + data
                                    .customProperties + '</span>' + escape(data.text) + '</div>';
                            }
                            return '<div>' + escape(data.text) + '</div>';
                        },
                    },
                });
            }
        });
    </script>
@endsection
@endsection
