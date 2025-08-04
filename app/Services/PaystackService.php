<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaystackService
{
    protected $baseUrl;
    protected $secretKey;

    public function __construct()
    {
        $this->baseUrl = config('services.paystack.url');
        $this->secretKey = config('services.paystack.secret_key');
    }

    public function initializeTransaction(array $data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Content-Type' => 'application/json'
        ])->post($this->baseUrl . '/transaction/initialize', $data);

        return $response->json();
    }

    public function verifyTransaction($reference)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Content-Type' => 'application/json'
        ])->get($this->baseUrl . '/transaction/verify/' . $reference);

        return $response->json();
    }

    public function listTransactions()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Content-Type' => 'application/json'
        ])->get($this->baseUrl . '/transaction');

        return $response->json();
    }
} 