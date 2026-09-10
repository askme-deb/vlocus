<?php

namespace Tests\Unit;

use App\Http\Controllers\Admin\TrackingController;
use Illuminate\Http\Request;
use Tests\TestCase;

class OrderTrackingRouteTest extends TestCase
{
    public function test_tracking_links_use_the_public_tracking_path(): void
    {
        $this->assertSame('/track-order?delivery_id=1&shop_id=2', route('order.tracking', [
            'delivery_id' => 1,
            'shop_id' => 2,
        ], false));

        $route = app('router')->getRoutes()->match(Request::create('/track-order?delivery_id=1&shop_id=2'));
        $this->assertSame(TrackingController::class . '@order_tracking', $route->getActionName());
        $this->assertNotContains('auth', $route->gatherMiddleware());
    }

    public function test_existing_tracking_links_still_resolve(): void
    {
        $route = app('router')->getRoutes()->match(Request::create('/order/1/2/track'));

        $this->assertSame(TrackingController::class . '@order_tracking', $route->getActionName());
        $this->assertSame('1', $route->parameter('delivery_id'));
        $this->assertSame('2', $route->parameter('shop_id'));
    }
}
