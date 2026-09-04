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

        $body = (array) $response->json();

        Log::info('BankU API response', [
            'endpoint' => $endpoint,
            'status' => $response->status(),
            // Key shape only (never values) -- lets us see what a response
            // actually contains (e.g. a photo field name) without logging PII.
            'keys' => $this->keyShape($body),
            // First 60 chars of the DL photo field, if present -- enough to
            // tell whether it's a URL or raw/data-URI base64, without
            // logging the full image data.
            'photo_preview' => is_string($photo = data_get($body, 'data.details_of_driving_licence.photo'))
                ? mb_substr($photo, 0, 60)
                : null,
        ]);

        return BankUResponse::fromArray($response->status(), (array) $response->json());
    }

    /**
     * Recursively collect an array's key names (not values), so response
     * shapes can be logged/inspected without leaking PII. A numerically
     * indexed list is summarised as its length plus its first item's keys.
     */
    private function keyShape(array $data, int $depth = 3): array|string
    {
        if ($depth <= 0) {
            return array_is_list($data) ? 'list[' . count($data) . ']' : 'object';
        }

        if (array_is_list($data)) {
            $firstItemShape = isset($data[0]) && is_array($data[0]) ? $this->keyShape($data[0], $depth - 1) : null;

            return 'list[' . count($data) . ']' . ($firstItemShape ? ' of ' . json_encode($firstItemShape) : '');
        }

        $shape = [];
        foreach ($data as $key => $value) {
            $shape[$key] = is_array($value) ? $this->keyShape($value, $depth - 1) : gettype($value);
        }

        return $shape;
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
