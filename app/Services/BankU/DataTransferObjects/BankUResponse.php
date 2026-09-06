<?php

namespace App\Services\BankU\DataTransferObjects;

class BankUResponse
{
    public function __construct(
        public readonly bool $success,
        public readonly int $statusCode,
        public readonly string $message,
        public readonly array $data,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(int $statusCode, array $body): self
    {
        $success = $statusCode >= 200 && $statusCode < 300 && ($body['success'] ?? false);

        return new self(
            success: $success,
            statusCode: $statusCode,
            message: $body['message'] ?? $body['error']['message'] ?? ($success ? 'Success' : 'Request failed.'),
            data: $body['data'] ?? [],
            raw: $body,
        );
    }

    public function bankAccountVerificationStatus(): ?string
    {
        if (! $this->success) {
            return null;
        }

        $value = $this->data['account_status'] ?? $this->data['bank_account_status'] ?? $this->data['status'] ?? null;
        if (! is_string($value)) {
            return null;
        }

        return match (strtoupper(trim($value))) {
            'VALID', 'VERIFIED' => 'verified',
            'INVALID', 'FAILED', 'REJECTED' => 'failed',
            'PENDING', 'PROCESSING', 'IN_PROGRESS', 'QUEUED' => 'pending',
            default => null,
        };
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
