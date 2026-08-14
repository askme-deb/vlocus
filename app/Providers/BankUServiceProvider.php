<?php

namespace App\Providers;

use App\Services\BankU\BankUClient;
use App\Services\BankU\BankUIdentityService;
use Illuminate\Support\ServiceProvider;

class BankUServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BankUClient::class, function () {
            $config = config('services.banku');

            return new BankUClient(
                baseUrl: $config['base_url'],
                clientId: $config['client_id'],
                clientSecret: $config['client_secret'],
                environment: $config['environment'],
                timeout: (int) $config['timeout'],
                connectTimeout: (int) $config['connect_timeout'],
                retryTimes: (int) $config['retry_times'],
                retryDelayMs: (int) $config['retry_delay_ms'],
            );
        });

        $this->app->singleton(BankUIdentityService::class, fn ($app) => new BankUIdentityService(
            $app->make(BankUClient::class)
        ));
    }
}
