<?php

namespace App\Services\BankU;

use App\Services\BankU\DataTransferObjects\BankUResponse;
use App\Services\BankU\Exceptions\BankUConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;

class BankUClient
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly int $timeout,
        private readonly int $connectTimeout,
        private readonly int $retryTimes,
        private readonly int $retryDelayMs,
    ) {
    }

    /**
     * Send a POST request to a BankU reseller API endpoint.
     *
     * BankU requires an Idempotency-Key header on every request. A fresh key
     * is generated per call; automatic retries reuse the same PendingRequest
     * (and therefore the same key), which is the exact-retry behaviour BankU
     * expects. Never pass the Client Secret or Encryption Key as the key.
     *
     * @throws BankUConnectionException
     */
    public function post(string $endpoint, array $payload, ?string $idempotencyKey = null): BankUResponse
    {
        $url = $this->endpointUrl($endpoint);
        $idempotencyKey ??= (string) Str::uuid();

        try {
            $response = $this->pendingRequest($idempotencyKey)->post($url, $payload);
        } catch (ConnectionException $e) {
            Log::error('BankU API connection error', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            throw new BankUConnectionException(
                "Unable to reach BankU service at [{$endpoint}].",
                previous: $e,
            );
        }

        Log::info('BankU API response', [
            'endpoint' => $endpoint,
            'status' => $response->status(),
        ]);

        return BankUResponse::fromArray($response->status(), (array) $response->json());
    }

    private function pendingRequest(string $idempotencyKey): PendingRequest
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Client-Id' => $this->clientId,
            'X-Client-Secret' => $this->clientSecret,
            'Idempotency-Key' => $idempotencyKey,
        ])
            ->timeout($this->timeout)
            ->connectTimeout($this->connectTimeout)
            ->retry($this->retryTimes, $this->retryDelayMs);
    }

    private function endpointUrl(string $endpoint): string
    {
        return rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');
    }
}
