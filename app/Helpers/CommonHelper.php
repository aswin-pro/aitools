
<?php

use App\Models\Config;
use App\Models\Currency;

if (! function_exists('formatCurrency')) {
    function formatCurrency($amount, $currencyCode = 'USD')
    {
        $config     = Config::get()->pluck('config_value', 'config_key');
        $currencies = Currency::get();

        $formatType = $config['currency_format_type'] ?? '1,234,567.89';
        $decimals   = (int) ($config['currency_decimals_place'] ?? 2);

        $currency = $currencies->firstWhere('iso_code', $currencyCode);

        $symbol      = $currency->symbol ?? '';
        $symbolFirst = ($currency->symbol_first ?? 'true') !== 'false';

        $formatted = match ($formatType) {
            '1,234,567.89' => number_format($amount, $decimals, '.', ','),
            '12,34,567.89' => formatIndianNumber($amount, $decimals),
            '1.234.567,89' => number_format($amount, $decimals, ',', '.'),
            '1 234 567,89' => number_format($amount, $decimals, ',', ' '),
            "1'234'567.89" => number_format($amount, $decimals, '.', "'"),
            default        => number_format($amount, $decimals, '.', ','),
        };

        return $symbolFirst ? $symbol . $formatted : $formatted . $symbol;
    }
}