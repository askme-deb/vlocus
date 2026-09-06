<?php

namespace Tests\Unit;

use App\Http\Controllers\Admin\DriverBankAccountController;
use App\Models\Driver;
use App\Models\DriverBankAccount;
use App\Models\User;
use App\Services\BankU\BankUClient;
use App\Services\BankU\BankUIdentityService;
use App\Services\BankU\DataTransferObjects\BankUResponse;
use App\Services\BankU\Exceptions\BankUConnectionException;
use App\Services\Wallet\CompanyWalletService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class DriverBankAccountTest extends TestCase
{
    private array $details = [
        'bank_name' => 'Test Bank',
        'branch_name' => 'Main',
        'account_number' => '001234567890',
        'account_holder_name' => 'Sample Driver',
        'ifsc' => 'HDFC0001234',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        // Never run the application's migrations or reset the development database.
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'cache.default' => 'array']);
        DB::purge('sqlite');
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->timestamps();
        });
        (require base_path('database/migrations/2026_09_06_000000_create_driver_bank_accounts_table.php'))->up();
        Driver::create(['company_id' => 7]);

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 42;
        $user->shouldReceive('companyId')->andReturn(7);
        $this->actingAs($user);
        Route::middleware('web')->post('/test-driver-bank', function (Request $request) {
            return app(DriverBankAccountController::class)->store($request, Driver::findOrFail(1));
        });
        Http::preventStrayRequests();
    }

    private function service()
    {
        $service = Mockery::mock(BankUIdentityService::class);
        $this->app->instance(BankUIdentityService::class, $service);
        return $service;
    }

    private function accepted(): BankUResponse
    {
        return BankUResponse::fromArray(202, ['success' => true, 'data' => ['status' => 'pending']]);
    }

    public function test_async_acknowledgement_is_pending_and_duplicate_submission_makes_no_extra_call(): void
    {
        $service = $this->service();
        $service->shouldReceive('verifyIfsc')->once()->andReturn($this->accepted());
        $service->shouldReceive('verifyBankAccount')->once()->andReturn($this->accepted());

        $this->post('/test-driver-bank', $this->details)->assertSessionHasNoErrors();
        $bank = DriverBankAccount::firstOrFail();
        $this->assertSame('pending', $bank->status);
        $this->assertNull($bank->verified_at);
        $this->assertSame('001234567890', $bank->account_number);
        $this->assertNotSame($bank->account_number, DB::table('driver_bank_accounts')->value('account_number'));
        $this->assertArrayNotHasKey('account_number', $bank->toArray());
        $this->post('/test-driver-bank', $this->details)->assertSessionHasNoErrors();
    }

    public function test_unknown_result_retries_with_identical_payload_and_key(): void
    {
        $service = $this->service();
        $service->shouldReceive('verifyIfsc')->once()->andReturn($this->accepted());
        $calls = [];
        $service->shouldReceive('verifyBankAccount')->twice()->andReturnUsing(function (...$args) use (&$calls) {
            $calls[] = $args;
            if (count($calls) === 1) {
                throw new BankUConnectionException('Timeout');
            }
            return $this->accepted();
        });
        $this->post('/test-driver-bank', $this->details)->assertSessionHasErrors('bank');
        $this->assertSame('unknown', DriverBankAccount::first()->status);
        $this->post('/test-driver-bank', $this->details)->assertSessionHasNoErrors();
        $this->assertSame($calls[0], $calls[1]);
        $this->assertSame('pending', DriverBankAccount::first()->status);
    }

    public function test_invalid_ifsc_stops_before_bank_submission(): void
    {
        $service = $this->service();
        $service->shouldReceive('verifyIfsc')->once()->andReturn(BankUResponse::fromArray(422, ['success' => false]));
        $service->shouldNotReceive('verifyBankAccount');
        $this->post('/test-driver-bank', $this->details)->assertSessionHasErrors('bank');
        $this->assertSame('failed', DriverBankAccount::first()->status);
    }

    public function test_other_company_cannot_submit_bank_details(): void
    {
        Driver::findOrFail(1)->update(['company_id' => 8]);
        $this->service()->shouldNotReceive('verifyIfsc');
        $this->post('/test-driver-bank', $this->details)->assertNotFound();
        $this->assertSame(0, DriverBankAccount::count());
    }

    public function test_invalid_account_format_never_calls_provider(): void
    {
        $this->service()->shouldNotReceive('verifyIfsc');
        $this->post('/test-driver-bank', array_replace($this->details, ['account_number' => '12ABC']))
            ->assertSessionHasErrors('account_number');
        $this->assertSame(0, DriverBankAccount::count());
    }

    public function test_service_sends_documented_payloads_and_operation_keys(): void
    {
        Http::fake(['banku.test/*' => Http::response(['success' => true, 'data' => []], 202)]);
        $client = new BankUClient('https://banku.test', 'test-client', 'test-secret', 15, 5, 2, 1);
        $service = new BankUIdentityService($client, Mockery::mock(CompanyWalletService::class));
        $service->verifyIfsc('HDFC0001234', 'reference-1', 'ifsc-operation', null);
        $service->verifyBankAccount('001234567890', 'HDFC0001234', 'Sample Driver', 'reference-1', 'bank-operation', null);
        Http::assertSent(fn ($r) => $r->url() === 'https://banku.test/api/reseller/v1/ifsc/verify'
            && $r->data() === ['ifsc' => 'HDFC0001234', 'verification_id' => 'reference-1']
            && $r->hasHeader('Idempotency-Key', 'ifsc-operation'));
        Http::assertSent(fn ($r) => $r->url() === 'https://banku.test/api/reseller/v1/bank-account/verify-async'
            && $r->data() === ['bank_account' => '001234567890', 'ifsc' => 'HDFC0001234', 'name' => 'Sample Driver', 'user_id' => 'reference_1']
            && $r->hasHeader('Idempotency-Key', 'bank-operation')
            && $r->hasHeader('X-Client-Id', 'test-client')
            && $r->hasHeader('X-Client-Secret', 'test-secret'));
        Http::assertSentCount(2);
    }

    public function test_bank_page_compiles_and_routes_use_the_bank_controller(): void
    {
        $compiled = app('blade.compiler')->compileString(file_get_contents(resource_path('views/admin/driver/bank.blade.php')));
        $this->assertNotEmpty(token_get_all($compiled, TOKEN_PARSE));
        foreach (['driver.bank.edit' => 'edit', 'driver.bank.store' => 'store'] as $name => $method) {
            $route = app('router')->getRoutes()->getByName($name);
            $this->assertSame(DriverBankAccountController::class . '@' . $method, $route->getActionName());
        }
        $this->assertSame('permission:Driver Create', DriverBankAccountController::middleware()[0]->middleware);
    }

    public function test_http_rejection_refunds_the_wallet_charge(): void
    {
        Http::fake(['banku.test/*' => Http::response(['success' => false], 422)]);
        $wallet = Mockery::mock(CompanyWalletService::class);
        $debit = new \App\Models\CompanyWalletTransaction(['id' => 1]);
        $wallet->shouldReceive('chargeForApiCall')->once()->with(7, 'bank', 42)->andReturn($debit);
        $wallet->shouldReceive('refund')->once()->with(7, $debit)->andReturn(new \App\Models\CompanyWalletTransaction());
        $client = new BankUClient('https://banku.test', 'test-client', 'test-secret', 15, 5, 2, 1);
        $service = new BankUIdentityService($client, $wallet);
        $this->expectException(\Illuminate\Http\Client\RequestException::class);
        $service->verifyBankAccount('001234567890', 'HDFC0001234', 'Sample Driver', 'reference-1', 'bank-operation', 7, 42);
    }


    public function test_provider_validation_error_is_preserved(): void
    {
        $response = BankUResponse::fromArray(422, [
            'success' => false,
            'error' => [
                'code' => 'PROVIDER_REJECTED',
                'message' => 'user_id should contain only alphanumeric and underscore characters.',
                'provider_code' => 'user_id_value_invalid',
            ],
        ]);
        $this->assertFalse($response->success);
        $this->assertSame('user_id should contain only alphanumeric and underscore characters.', $response->message);
    }


    private function pendingStatusService()
    {
        $service = $this->service();
        $service->shouldReceive('verifyIfsc')->once()->andReturn($this->accepted());
        $service->shouldReceive('verifyBankAccount')->once()->andReturn($this->accepted());
        $this->post('/test-driver-bank', $this->details)->assertSessionHasNoErrors();
        Route::middleware('web')->post('/test-driver-bank-status', function () {
            return app(DriverBankAccountController::class)->checkStatus(Driver::findOrFail(1));
        });
        return $service;
    }

    public function test_status_lookup_updates_verified_account(): void
    {
        $service = $this->pendingStatusService();
        $reference = DriverBankAccount::first()->verification_reference;
        $service->shouldReceive('bankAccountStatus')->once()->with($reference)
            ->andReturn(BankUResponse::fromArray(200, ['success' => true, 'data' => ['account_status' => 'VALID']]));
        $this->post('/test-driver-bank-status')->assertSessionHasNoErrors();
        $bank = DriverBankAccount::first();
        $this->assertSame('verified', $bank->status);
        $this->assertNotNull($bank->verified_at);
    }

    public function test_status_api_error_keeps_account_pending(): void
    {
        $service = $this->pendingStatusService();
        $service->shouldReceive('bankAccountStatus')->once()
            ->andReturn(BankUResponse::fromArray(403, ['success' => false, 'error' => [
                'code' => 'API_NOT_ACTIVATED',
                'message' => 'This API requires organisation approval in BankU Control.',
            ]]));
        $this->post('/test-driver-bank-status')->assertSessionHasErrors('bank');
        $this->assertSame('pending', DriverBankAccount::first()->status);
        $this->assertNull(DriverBankAccount::first()->verified_at);
    }

    public function test_status_lookup_rejects_other_company(): void
    {
        $service = $this->pendingStatusService();
        $service->shouldNotReceive('bankAccountStatus');
        Driver::findOrFail(1)->update(['company_id' => 8]);
        $this->post('/test-driver-bank-status')->assertNotFound();
    }

    public function test_account_status_requires_explicit_validation_result(): void
    {
        foreach (['VALID' => 'verified', 'INVALID' => 'failed', 'PENDING' => 'pending', 'SUCCESS' => null, 'unexpected' => null] as $input => $expected) {
            $result = BankUResponse::fromArray(200, ['success' => true, 'data' => ['status' => $input]]);
            $this->assertSame($expected, $result->bankAccountVerificationStatus());
        }
        $this->assertNull(BankUResponse::fromArray(200, ['success' => true])->bankAccountVerificationStatus());
    }

    public function test_status_lookup_uses_original_user_id_without_wallet_charge(): void
    {
        Http::fake(['banku.test/*' => Http::response(['success' => true, 'data' => ['status' => 'PENDING']])]);
        $wallet = Mockery::mock(CompanyWalletService::class);
        $wallet->shouldNotReceive('chargeForApiCall');
        $client = new BankUClient('https://banku.test', 'test-client', 'test-secret', 15, 5, 2, 1);
        $service = new BankUIdentityService($client, $wallet);
        $service->bankAccountStatus('original-reference');
        $service->bankAccountStatus('original-reference');
        $keys = [];
        foreach (Http::recorded() as [$request]) {
            $this->assertSame('https://banku.test/api/reseller/v1/bank-account/status', $request->url());
            $this->assertSame(['user_id' => 'original_reference'], $request->data());
            $keys[] = $request->header('Idempotency-Key')[0];
        }
        $this->assertNotSame($keys[0], $keys[1]);
        $this->assertTrue(\Illuminate\Support\Str::isUuid($keys[0]));
    }

}
