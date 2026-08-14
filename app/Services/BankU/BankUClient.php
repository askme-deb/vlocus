<?php

namespace App\Services\BankU;

use App\Services\BankU\DataTransferObjects\BankUResponse;
use App\Services\BankU\Exceptions\BankUConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;

class BankUClient
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $environment,
        private readonly int $timeout,
        private readonly int $connectTimeout,
        private readonly int $retryTimes,
        private readonly int $retryDelayMs,
    ) {
    }

    /**
     * Send a POST request to a BankU reseller API endpoint.
     *
     * @throws BankUConnectionException
     */
    public function post(string $endpoint, array $payload): BankUResponse
    {
        $url = $this->endpointUrl($endpoint);

        try {
            $response = $this->pendingRequest()->post($url, $payload);
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

    private function pendingRequest(): PendingRequest
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Client-Id' => $this->clientId,
            'X-Client-Secret' => $this->clientSecret,
            'X-BankU-Environment' => $this->environment,
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
