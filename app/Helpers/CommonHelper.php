<?php

use App\Models\Config;
use App\Models\Currency;

// get config value
if (! function_exists('getConfigValue')) {
    function getConfigValue(string $key)
    {
        // config
        $config = Config::get()->pluck('config_value', 'config_key');

        // return value
        return $config[$key] ?? null;
    }
}

// format currency
if (! function_exists('formatCurrency')) {
    function formatCurrency(mixed $amount, string $currencyCode = 'USD')
    {
        // config
        $config = Config::get()->pluck('config_value', 'config_key');

        // currencies
        $currencies = Currency::get();

        // format type
        $formatType = $config['currency_format_type'] ?? '1,234,567.89';

        // decimals
        $decimals = (int) ($config['currency_decimals_place'] ?? 2);

        // currency
        $currency = $currencies->firstWhere('iso_code', $currencyCode);

        // symbol
        $symbol = $currency->symbol ?? '';

        // check symbol first
        $symbolFirst = ($currency->symbol_first ?? 'true') !== 'false';

        // format amount
        $formatted = match ($formatType) {
            '1,234,567.89' => number_format($amount, $decimals, '.', ','),
            '12,34,567.89' => formatIndianNumber($amount, $decimals),
            '1.234.567,89' => number_format($amount, $decimals, ',', '.'),
            '1 234 567,89' => number_format($amount, $decimals, ',', ' '),
            "1'234'567.89" => number_format($amount, $decimals, '.', "'"),
            default        => number_format($amount, $decimals, '.', ','),
        };

        // return amount with symbol
        return $symbolFirst ? $symbol . $formatted : $formatted . $symbol;
    }
}

if (!function_exists('formatIndianNumber')) {
    function formatIndianNumber(mixed $amount, int $setDecimalsPlaces = 2)
    {
        $amount = number_format($amount, $setDecimalsPlaces, '.', '');

        [$integerPart, $decimalPart] = array_pad(explode('.', $amount), 2, '00');
        $lastThreeDigits = substr($integerPart, -3);
        $otherDigits = substr($integerPart, 0, -3);

        if ($otherDigits !== '') {
            $otherDigits = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $otherDigits);
            $formattedInteger = $otherDigits . ',' . $lastThreeDigits;
        } else {
            $formattedInteger = $lastThreeDigits;
        }

        return $formattedInteger . '.' . $decimalPart;
    }
}

// gat app languages
if (! function_exists('getAppLanguages')) {
    function getAppLanguages()
    {
        // return languages
        return config('app.languages');
    }
}
