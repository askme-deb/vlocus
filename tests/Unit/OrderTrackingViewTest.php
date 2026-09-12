<?php

namespace Tests\Unit;

use App\Models\DeliveryScheduleShop;
use App\Models\DeliverySchedule;
use App\Models\Driver;
use App\Models\Shop;
use App\Models\User;
use App\Models\Vehicle;
use Tests\TestCase;

class OrderTrackingViewTest extends TestCase
{
    /**
     * Builds the full graph of unsaved (never persisted) models the public
     * tracking page needs, so the Blade template can be rendered and
     * asserted on without touching the database.
     *
     * @return array{0: DeliveryScheduleShop, 1: DeliverySchedule, 2: User, 3: Driver, 4: Vehicle, 5: Shop}
     */
    private function buildOrder(array $overrides = []): array
    {
        $shop = new Shop([
            'shop_name' => 'Test Shop',
            'shop_address' => '123 Main Road',
            'shop_district' => 'Kolkata',
            'shop_state' => 'West Bengal',
            'shop_pincode' => '700001',
            'shop_contact_person_name' => 'Abhijit Biswas',
            'shop_contact_person_phone' => '9038200000',
            'shop_latitude' => '22.5050',
            'shop_longitude' => '88.3520',
        ]);

        $vehicle = new Vehicle(['vehicle_number' => 'WB-06-AB-1234']);

        $driverUser = new User(['name' => 'Raja', 'phone' => '9038211111']);

        $driverDetails = new Driver(['latitude' => '22.5000', 'longitude' => '88.3400']);

        $delivery = new DeliverySchedule([
            'order_id' => 'O20260913120000',
            'amount' => '1299',
            'payment_type' => 'prepaid',
        ]);

        $order = new DeliveryScheduleShop(array_merge([
            'amount' => '1299',
            'payment_type' => 'prepaid',
            'invoice_no' => 'INV-1001',
            'is_accepted' => 0,
            'is_delivered' => 0,
            'is_cancelled' => 0,
        ], $overrides));
        $order->tracking_token = str_repeat('a', 40);
        $order->setRelation('shop', $shop);
        $order->setRelation('deliverySchedule', $delivery);

        return [$order, $delivery, $driverUser, $driverDetails, $vehicle, $shop];
    }

    private function render(array $overrides = [], ?int $etaMinutes = 6): string
    {
        [$order, $delivery, $driverUser, $driverDetails, $vehicle, $shop] = $this->buildOrder($overrides);

        return view('order_tracking', [
            'order' => $order,
            'delivery' => $delivery,
            'driver' => $driverUser,
            'driver_details' => $driverDetails,
            'vehicle' => $vehicle,
            'shop' => $shop,
            'products' => collect(),
            'etaMinutes' => $etaMinutes,
        ])->render();
    }

    public function test_renders_an_in_progress_order_with_dynamic_data(): void
    {
        $html = $this->render(['is_accepted' => 1]);

        $this->assertStringContainsString('Order #O20260913120000', $html);
        $this->assertStringContainsString('WB-06-AB-1234', $html);
        $this->assertStringContainsString('Raja', $html);
        $this->assertStringContainsString('Arriving in <span id="etaMap">6</span> minutes', $html);
        $this->assertStringContainsString('90382XXXXX', $html);
    }

    public function test_renders_a_delivered_order_without_eta_countdown(): void
    {
        $html = $this->render(['is_accepted' => 1, 'is_delivered' => 1], etaMinutes: null);

        $this->assertStringContainsString('Order delivered', $html);
        $this->assertStringNotContainsString('id="etaMap"', $html);
    }

    public function test_renders_a_cancelled_order_with_a_cancellation_banner(): void
    {
        $html = $this->render(['is_cancelled' => 1, 'cancel_reason' => 'Shop was closed'], etaMinutes: null);

        $this->assertStringContainsString('This order was cancelled', $html);
        $this->assertStringContainsString('Shop was closed', $html);
    }

    public function test_renders_the_not_found_page_for_an_invalid_token(): void
    {
        $html = view('order_tracking_unavailable')->render();

        $this->assertStringContainsString('Tracking link not found', $html);
    }
}
