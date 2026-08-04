<?php

use App\Models\Config;
use App\Models\Currency;

if (!function_exists('currency')) {
    function currency($amount)
    {
        // Fetch configuration values
        $config = Config::get();
        $currencies = Currency::get();

        $setCurrencyCode = $config[1]->config_value ?? 'USD'; // Default fallback
        $formatType = $config[62]->config_value ?? '1.234.567,89';
        $setDecimalsPlaces = (int)($config[63]->config_value ?? 2);

        // Initialize currency variables
        $currencySymbol = '';
        $symbolFirst = true;

        foreach ($currencies as $currency) {
            if ($currency->iso_code === $setCurrencyCode) {
                $currencySymbol = $currency->symbol;
                $symbolFirst = $currency->symbol_first !== "false";
                break;
            }
        }

        // Format the amount based on format type
        $formattedAmount = match ($formatType) {
            '1,234,567.89' => number_format($amount, $setDecimalsPlaces, '.', ','),
            '12,34,567.89' => formatIndianNumber($amount, $setDecimalsPlaces),
            '1.234.567,89' => number_format($amount, $setDecimalsPlaces, ',', '.'),
            '1 234 567,89' => number_format($amount, $setDecimalsPlaces, ',', ' '),
            "1'234'567.89" => number_format($amount, $setDecimalsPlaces, '.', "'"),
            default => number_format($amount, $setDecimalsPlaces, '.', ','),
        };

        return $symbolFirst ? $currencySymbol . $formattedAmount : $formattedAmount . $currencySymbol;
    }
}

if (!function_exists('formatIndianNumber')) {
    function formatIndianNumber($amount, $setDecimalsPlaces = 2)
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
