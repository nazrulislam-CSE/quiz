<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class RechargeApiService
{
    private string $baseUrl;
    private string $apiKey;
    private string $apiSecret;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.recharge.base_url'), '/');
        $this->apiKey = config('services.recharge.api_key');
        $this->apiSecret = config('services.recharge.api_secret');
    }

    /**
     * Send recharge request
     */
    public function recharge(
        string $number,
        float|int $amount,
        string $transactionId,
        string $operator
    ): array {
        $response = Http::timeout(30)
            ->acceptJson()
            ->post($this->baseUrl . '/recharge', [
                'api_key'        => $this->apiKey,
                'api_secret'     => $this->apiSecret,
                'number'         => $number,
                'amount'         => $amount,
                'transaction_id' => $transactionId,
                'operator'       => $operator,
            ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'Recharge API Error: ' . $response->body()
            );
        }

        return $response->json();
    }

    /**
     * Check transaction status
     *
     * Adjust the payload below according to the /api/status
     * API's required parameters.
     */
    public function status(string $transactionId): array
    {
        $response = Http::timeout(30)
            ->acceptJson()
            ->post($this->baseUrl . '/status', [
                'api_key'        => $this->apiKey,
                'api_secret'     => $this->apiSecret,
                'transaction_id' => $transactionId,
            ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'Status API Error: ' . $response->body()
            );
        }

        return $response->json();
    }
}
