<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    protected $currencies = [
        'NGN' => [
            'name' => 'Nigerian Naira',
            'symbol' => '₦',
            'code' => 'NGN',
            'exchange_rate' => 1.0,
            'countries' => ['NG']
        ],
        'USD' => [
            'name' => 'US Dollar',
            'symbol' => '$',
            'code' => 'USD',
            'exchange_rate' => 0.00083, // 1 NGN = 0.00083 USD (approximately 1200 NGN = 1 USD)
            'countries' => ['US', 'CA', 'GB', 'AU', 'EU'] // Add more countries as needed
        ]
    ];

    public function getCurrencies()
    {
        return $this->currencies;
    }

    public function getCurrencyByCode($code)
    {
        return $this->currencies[$code] ?? null;
    }

        public function detectCurrencyFromIP(Request $request)
    {
        $ip = $request->ip();
        Log::info("Detecting currency for IP: {$ip}");
        
        // Cache the result for 24 hours to avoid repeated API calls
        return Cache::remember("currency_ip_{$ip}", 60 * 24, function () use ($ip) {
            try {
                // Try multiple IP geolocation services for better reliability
                $services = [
                    "http://ip-api.com/json/{$ip}",
                    "https://ipapi.co/{$ip}/json/",
                    "https://api.ipgeolocation.io/ipgeo?apiKey=free&ip={$ip}"
                ];
                
                foreach ($services as $service) {
                    try {
                        $context = stream_context_create([
                            'http' => [
                                'timeout' => 5,
                                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                            ]
                        ]);
                        
                        $response = file_get_contents($service, false, $context);
                        $data = json_decode($response, true);
                        
                        Log::info("API Response from {$service}: " . json_encode($data));
                        
                        if ($data) {
                            $countryCode = null;
                            
                            // Different APIs return country code in different fields
                            if (isset($data['countryCode'])) {
                                $countryCode = $data['countryCode'];
                            } elseif (isset($data['country_code'])) {
                                $countryCode = $data['country_code'];
                            } elseif (isset($data['country_code2'])) {
                                $countryCode = $data['country_code2'];
                            }
                            
                            if ($countryCode) {
                                Log::info("IP {$ip} detected as country: {$countryCode}");
                                
                                // Check if it's Nigeria
                                if ($countryCode === 'NG') {
                                    return 'NGN';
                                }
                                
                                // For all other countries, default to USD
                                return 'USD';
                            } else {
                                Log::warning("No country code found in API response from {$service}");
                            }
                        } else {
                            Log::warning("Invalid or empty response from {$service}");
                        }
                    } catch (\Exception $e) {
                        Log::warning("Failed to detect currency from service {$service}: " . $e->getMessage());
                        continue;
                    }
                }
                
                // If we get here, all APIs failed or returned no country code
                Log::warning("All IP geolocation services failed or returned no country code for IP: {$ip}");
                
            } catch (\Exception $e) {
                // Log error but don't fail
                Log::warning('Failed to detect currency from IP: ' . $e->getMessage());
            }
            
            // Default to NGN if detection fails (since you're in Nigeria)
            Log::info("Defaulting to NGN for IP: {$ip} - API detection failed");
            return 'NGN';
        });
    }

    public function getCurrentCurrency(Request $request = null)
    {
        if (!$request) {
            $request = request();
        }
        
        // First check if user has manually selected a currency
        $selectedCurrency = $request->cookie('selected_currency');
        
        if ($selectedCurrency && isset($this->currencies[$selectedCurrency])) {
            return $selectedCurrency;
        }
                        Log::info($this->detectCurrencyFromIP($request));
        
        // If no manual selection, detect from IP
        $detectedCurrency = $this->detectCurrencyFromIP($request);
        Log::info("Detected currency: {$detectedCurrency}");
        return $detectedCurrency;
    }

    public function formatPrice($price, $currencyCode = null, Request $request = null)
    {
        if (!$currencyCode) {
            $currencyCode = $this->getCurrentCurrency($request);
        }
        
        $currency = $this->getCurrencyByCode($currencyCode);
        
        if (!$currency) {
            return '₦' . number_format($price, 2);
        }
        
        // Convert price if needed
        $convertedPrice = $this->convertPrice($price, 'NGN', $currencyCode);
        
        return $currency['symbol'] . number_format($convertedPrice, 2);
    }
        public function formatPriceRaw($price, $currencyCode = null, Request $request = null)
    {
        if (!$currencyCode) {
            $currencyCode = $this->getCurrentCurrency($request);
        }
        
        $currency = $this->getCurrencyByCode($currencyCode);
        
        if (!$currency) {
            return $price;
        }
        
        // Convert price if needed
        $convertedPrice = $this->convertPrice($price, 'NGN', $currencyCode);
        
        return $convertedPrice;
    }

    public function convertPrice($price, $fromCurrency, $toCurrency)
    {
        if ($fromCurrency === $toCurrency) {
            return $price;
        }
        
        // Try to get real-time rate first
        if ($fromCurrency === 'NGN' && $toCurrency === 'USD') {
            $rate = $this->getRealTimeExchangeRate('NGN', 'USD');
            return $price * $rate;
        } elseif ($fromCurrency === 'USD' && $toCurrency === 'NGN') {
            $rate = $this->getRealTimeExchangeRate('NGN', 'USD');
            return $price / $rate;
        }
        
        // Fallback to static rates
        $fromRate = $this->currencies[$fromCurrency]['exchange_rate'] ?? 1;
        $toRate = $this->currencies[$toCurrency]['exchange_rate'] ?? 1;
        
        // Convert through NGN as base currency
        $inNGN = $price / $fromRate;
        return $inNGN * $toRate;
    }

    public function getCurrencySymbol($currencyCode = null, Request $request = null)
    {
        if (!$currencyCode) {
            $currencyCode = $this->getCurrentCurrency($request);
        }
        
        $currency = $this->getCurrencyByCode($currencyCode);
        return $currency['symbol'] ?? '₦';
    }

    public function getRealTimeExchangeRate($fromCurrency = 'NGN', $toCurrency = 'USD')
    {
        // Cache exchange rate for 1 hour
        return Cache::remember("exchange_rate_{$fromCurrency}_{$toCurrency}", 60, function () use ($fromCurrency, $toCurrency) {
            try {
                // Use a free exchange rate API
                $url = "https://api.exchangerate-api.com/v4/latest/{$fromCurrency}";
                
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 10,
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                    ]
                ]);
                
                $response = file_get_contents($url, false, $context);
                $data = json_decode($response, true);
                
                if ($data && isset($data['rates'][$toCurrency])) {
                    $rate = $data['rates'][$toCurrency];
                    Log::info("Real-time exchange rate {$fromCurrency} to {$toCurrency}: {$rate}");
                    return $rate;
                }
            } catch (\Exception $e) {
                Log::warning('Failed to get real-time exchange rate: ' . $e->getMessage());
            }
            
            // Fallback to static rate
            return $this->currencies[$toCurrency]['exchange_rate'] ?? 1;
        });
    }

    public function updateExchangeRates()
    {
        try {
            $usdRate = $this->getRealTimeExchangeRate('NGN', 'USD');
            if ($usdRate > 0) {
                $this->currencies['USD']['exchange_rate'] = $usdRate;
                Log::info("Updated USD exchange rate to: {$usdRate}");
            }
        } catch (\Exception $e) {
            Log::warning('Failed to update exchange rates: ' . $e->getMessage());
        }
    }
} 