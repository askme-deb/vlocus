<?php

namespace Tests\Unit;

use App\Http\Controllers\Admin\TrackingController;
use Illuminate\Http\Request;
use Tests\TestCase;

class OrderTrackingRouteTest extends TestCase
{
    /**
     * The public tracking link is addressed by an opaque tracking_token,
     * never by the delivery/shop row's own sequential id - so it can be
     * handed to a customer over WhatsApp without exposing or letting
     * anyone enumerate internal database ids.
     */
    public function test_tracking_links_use_an_opaque_token_not_a_sequential_id(): void
    {
        $token = str_repeat('a', 40);

        $this->assertSame('/track/' . $token, route('order.tracking', [
            'token' => $token,
        ], false));

        $route = app('router')->getRoutes()->match(Request::create('/track/' . $token));
        $this->assertSame(TrackingController::class . '@order_tracking', $route->getActionName());
        $this->assertSame($token, $route->parameter('token'));
        $this->assertNotContains('auth', $route->gatherMiddleware());
    }

    public function test_legacy_id_based_tracking_links_no_longer_resolve(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        app('router')->getRoutes()->match(Request::create('/order/1/2/track'));
    }

    public function test_tracking_side_routes_are_public_and_token_scoped(): void
    {
        foreach ([
            ['GET', '/track/' . str_repeat('a', 40) . '/location', 'order.tracking.location'],
            ['POST', '/track/' . str_repeat('a', 40) . '/contact', 'order.tracking.contact'],
            ['POST', '/track/' . str_repeat('a', 40) . '/rating', 'order.tracking.rating'],
        ] as [$method, $uri, $name]) {
            $route = app('router')->getRoutes()->match(Request::create($uri, $method));

            $this->assertSame($name, $route->getName());
            $this->assertNotContains('auth', $route->gatherMiddleware());
        }
    }
}
