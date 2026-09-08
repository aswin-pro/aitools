<?php

use Illuminate\Support\Collection;

if (! function_exists('getTaxPrice')) {
    function getTaxPrice(Collection $config, float $amount): float
    {
        // tax price
        $taxPrice = ($amount * ($config[25]->config_value / 100));

        // tax amount
        return round($taxPrice, 2);
    }
}
