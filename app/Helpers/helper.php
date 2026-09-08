<?php

use App\Models\Config;
use App\Models\Currency;

if (! function_exists('currency')) {
    function currency($amount)
    {
        $config = Config::get();
        $currency = Currency::where(
            'iso_code',
            $config['1']->config_value
        )->first();

        if (!$currency) {
            return number_format((float) $amount, 2);
        }

        $formatted = number_format(
            (float) $amount,
            2,
            $currency->decimal_mark ?: '.',
            $currency->thousands_separator ?: ','
        );

        $symbol = $currency->html_entity ?: $currency->symbol;

        return $currency->symbol_first
            ? $symbol . $formatted
            : $formatted . $symbol;
    }
}