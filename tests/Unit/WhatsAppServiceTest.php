<?php

namespace Tests\Unit;

use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppServiceTest extends TestCase
{
    public function test_template_request_matches_pinbot_payload(): void
    {
        config(['services.whatsapp' => [
            'enabled' => true,
            'base_url' => 'https://partnersv1.pinbot.ai/v3',
            'phone_number_id' => '1222479320957042',
            'api_key' => 'test-key',
            'template_name' => 'ordernotification',
            'template_language' => 'en',
        ]]);
        Http::preventStrayRequests();
        Http::fake(['*' => Http::response(['messages' => [['id' => 'test']]], 200)]);
        $values = ['test1', 'test2', 'test3', 'test4', 'test5', 'test6'];

        $this->assertTrue((new WhatsAppService)->sendTemplate('7385553387', $values));

        Http::assertSent(fn ($request) =>
            $request->url() === 'https://partnersv1.pinbot.ai/v3/1222479320957042/messages'
            && $request->hasHeader('apikey', 'test-key')
            && $request->data() === [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'INDIVIDUAL',
                'to' => '917385553387',
                'type' => 'template',
                'template' => [
                    'language' => ['code' => 'en'],
                    'name' => 'ordernotification',
                    'components' => [[
                        'type' => 'body',
                        'parameters' => array_map(fn ($value) => ['type' => 'text', 'text' => $value], $values),
                    ]],
                ],
            ]
        );
        Http::assertSentCount(1);
    }

    public function test_incomplete_template_is_not_sent(): void
    {
        Http::fake();
        $this->assertFalse((new WhatsAppService)->sendTemplate('7385553387', ['test1']));
        Http::assertNothingSent();
    }

    public function test_order_notification_sends_six_order_fields_as_a_template(): void
    {
        config(['services.whatsapp' => [
            'enabled' => true,
            'base_url' => 'https://partnersv1.pinbot.ai/v3',
            'phone_number_id' => '1222479320957042',
            'api_key' => 'test-key',
        ]]);
        Http::preventStrayRequests();
        Http::fake(['*' => Http::response([], 200)]);

        $this->assertTrue((new WhatsAppService)->sendOrderPlacedNotification([
            'customer_name' => 'Sample Customer',
            'customer_phone' => '7385553387',
            'order_no' => 'ORD-123',
            'order_items' => "Item A x2\nItem B x1",
            'vehicle_no' => 'MH12AB1234',
            'driver_name' => 'Driver',
            'driver_contact' => '911234567890',
            'tracking_url' => 'https://example.com/tracking/123',
        ]));

        Http::assertSent(fn ($request) => $request['type'] === 'template'
            && $request['to'] === '917385553387'
            && $request['template']['name'] === 'ordernotification'
            && array_column($request['template']['components'][0]['parameters'], 'text') === [
                'Sample Customer', 'ORD-123', 'MH12AB1234',
                'Driver', '911234567890', 'https://example.com/tracking/123',
            ]);
        Http::assertSentCount(1);
    }
}
