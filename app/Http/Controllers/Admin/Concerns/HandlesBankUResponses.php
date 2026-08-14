<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Services\BankU\DataTransferObjects\BankUResponse;
use Illuminate\Http\Request;

/**
 * Shared response-shaping helpers for any admin controller calling a BankU
 * verification endpoint (PAN/Aadhaar identity docs, vehicle RC, ...).
 */
trait HandlesBankUResponses
{
    private function bankUResponse(BankUResponse $result, string $failureFallback)
    {
        if (! $result->success) {
            return response()->json([
                'success' => false,
                'message' => $result->message ?: $failureFallback,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => $result->data,
        ]);
    }

    private function bankUUnavailableResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Verification service is currently unavailable. Please try again shortly.',
        ], 503);
    }

    /**
     * Decode a verified-payload hidden field submitted alongside the form.
     * Returns null when the field is missing/empty/malformed, so the caller
     * only persists verification data that genuinely came from BankU.
     */
    private function decodeVerificationPayload(Request $request, string $field): ?array
    {
        $decoded = json_decode((string) $request->input($field), true);

        if (json_last_error() !== JSON_ERROR_NONE || empty($decoded)) {
            return null;
        }

        return $decoded;
    }
}
