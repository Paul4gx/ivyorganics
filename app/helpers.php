<?php

use App\Services\CurrencyService;

if (!function_exists('format_currency')) {
    function format_currency($price, $currencyCode = null)
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->formatPrice($price, $currencyCode, request());
    }
}

if (!function_exists('format_currency_raw')) {
    function format_currency_raw($price, $currencyCode = null)
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->formatPriceRaw($price, $currencyCode, request());
    }
}

if (!function_exists('get_currency_symbol')) {
    function get_currency_symbol($currencyCode = null)
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->getCurrencySymbol($currencyCode, request());
    }
}

if (!function_exists('get_current_currency')) {
    function get_current_currency()
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->getCurrentCurrency(request());
    }
} 