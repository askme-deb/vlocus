<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin wrapper around the Pinbot / WhatsApp Business cloud API.
 *
 * Every public method fails soft: on a missing config, an invalid number or a
 * transport/API error it logs and returns false instead of throwing, so a
 * notification never blocks the business action that triggered it.
 */
class WhatsAppService
{
    private bool $enabled;
    private string $baseUrl;
    private ?string $phoneNumberId;
    private ?string $apiKey;
    private string $defaultCountryCode;
    private int $timeout;

    public function __construct()
    {
        $config = (array) config('services.whatsapp');

        $this->enabled = (bool) ($config['enabled'] ?? false);
        $this->baseUrl = rtrim((string) ($config['base_url'] ?? ''), '/');
        $this->phoneNumberId = $config['phone_number_id'] ?? null;
        $this->apiKey = $config['api_key'] ?? null;
        $this->defaultCountryCode = (string) ($config['default_country_code'] ?? '91');
        $this->timeout = (int) ($config['timeout'] ?? 15);
    }

    /**
     * Whether the service has everything it needs to talk to the API.
     */
    public function isConfigured(): bool
    {
        return $this->enabled
            && $this->baseUrl !== ''
            && ! empty($this->phoneNumberId)
            && ! empty($this->apiKey);
    }

    /**
     * Send a plain-text WhatsApp message.
     */
    public function sendText(string $to, string $body, bool $previewUrl = true): bool
    {
        return $this->sendMessage($to, [
            'preview_url' => $previewUrl,
            'type' => 'text',
            'text' => ['body' => $body],
        ]);
    }

    /**
     * Send the approved ordernotification template with its six body values.
     * Values must be supplied in the approved template's placeholder order.
     */
    public function sendTemplate(string $to, array $parameters): bool
    {
        if (count($parameters) !== 6) {
            Log::warning('WhatsApp: order template requires six body parameters');

            return false;
        }

        return $this->sendMessage($to, [
            'type' => 'template',
            'template' => [
                'language' => ['code' => config('services.whatsapp.template_language', 'en')],
                'name' => config('services.whatsapp.template_name', 'ordernotification'),
                'components' => [[
                    'type' => 'body',
                    'parameters' => array_map(static fn ($value) => [
                        'type' => 'text',
                        'text' => (string) $value,
                    ], array_values($parameters)),
                ]],
            ],
        ]);
    }

    private function sendMessage(string $to, array $message): bool
    {
        if (! $this->isConfigured()) {
            Log::warning('WhatsApp: service not configured, message skipped', ['to' => $to]);

            return false;
        }

        $recipient = $this->normalizeNumber($to);

        if ($recipient === null) {
            Log::warning('WhatsApp: invalid recipient number, message skipped', ['to' => $to]);

            return false;
        }

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'apikey' => $this->apiKey,
                ])
                ->post("{$this->baseUrl}/{$this->phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'INDIVIDUAL',
                    'to' => $recipient,
                ] + $message);

            if ($response->failed()) {
                Log::error('WhatsApp: send failed', [
                    'to' => $recipient,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('WhatsApp: send threw', [
                'to' => $recipient,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Notify a shop owner that their delivery order has been placed.
     *
     * Expected keys: customer_phone, order_no, order_items,
     * vehicle_no, driver_name, driver_contact, tracking_url.
     */
    public function sendOrderPlacedNotification(array $data): bool
    {
        $phone = (string) ($data['customer_phone'] ?? '');

        if ($phone === '') {
            Log::warning('WhatsApp: order notification skipped, no customer phone', [
                'order_no' => $data['order_no'] ?? null,
            ]);

            return false;
        }

        // Approved template: greeting name, order number, vehicle, driver, contact, tracking URL.
        $parameters = array_map(static function (string $key) use ($data): string {
            $value = trim(preg_replace('/\s+/u', ' ', (string) ($data[$key] ?? '')) ?? '');

            return $value !== '' ? $value : '-';
        }, ['customer_name', 'order_no', 'vehicle_no', 'driver_name', 'driver_contact', 'tracking_url']);

        return $this->sendTemplate($phone, $parameters);
    }
    /**
     * Reduce a user-entered phone number to the digits-only E.164-style form
     * the API expects (country code + subscriber number, no '+'). Returns null
     * when nothing usable is left.
     */
    private function normalizeNumber(?string $number): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $number) ?? '';
        $digits = ltrim($digits, '0');

        if ($digits === '') {
            return null;
        }

        // A bare local subscriber number - prepend the default country code.
        if (strlen($digits) === 10) {
            $digits = $this->defaultCountryCode . $digits;
        }

        return strlen($digits) >= 11 ? $digits : null;
    }
}
