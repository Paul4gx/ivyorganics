<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function switch(Request $request)
    {
        $currency = $request->input('currency');
        $currencies = $this->currencyService->getCurrencies();
        
        if (!isset($currencies[$currency])) {
            return back()->with('error', 'Invalid currency selected.');
        }
        
        // Set cookie for 30 days
        return back()->cookie('selected_currency', $currency, 60 * 24 * 30)
                    ->with('success', 'Currency updated successfully.');
    }

    public function switchAjax(Request $request)
    {
        $currency = $request->input('currency');
        $currencies = $this->currencyService->getCurrencies();
        
        if (!isset($currencies[$currency])) {
            return response()->json(['error' => 'Invalid currency selected.'], 400);
        }
        
        // Set cookie for 30 days
        $response = response()->json(['success' => true, 'currency' => $currency]);
        $response->cookie('selected_currency', $currency, 60 * 24 * 30);
        
        return $response;
    }

    public function getCurrentCurrency(Request $request)
    {
        $currentCurrency = $this->currencyService->getCurrentCurrency($request);
        $symbol = $this->currencyService->getCurrencySymbol(null, $request);
        $ip = $request->ip();
        
        // Add debugging information
        $debug = [
            'ip' => $ip,
            'detected_currency' => $this->currencyService->detectCurrencyFromIP($request),
            'cookie_currency' => $request->cookie('selected_currency'),
            'current_currency' => $currentCurrency,
            'symbol' => $symbol
        ];
        
        return response()->json([
            'currency' => $currentCurrency,
            'symbol' => $symbol,
            'debug' => $debug
        ]);
    }

    public function clearCache(Request $request)
    {
        $ip = $request->ip();
        \Illuminate\Support\Facades\Cache::forget("currency_ip_{$ip}");
        \Illuminate\Support\Facades\Cache::forget("exchange_rate_NGN_USD");
        
        return response()->json([
            'message' => 'Currency cache cleared',
            'ip' => $ip
        ]);
    }
} 