<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Currency;
use App\Services\CurrencyService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CurrencyServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $currencyService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->currencyService = new CurrencyService();
    }

    public function test_can_get_default_currency()
    {
        Currency::create([
            'code' => 'MMK',
            'name' => 'Myanmar Kyat',
            'symbol' => 'K',
            'exchange_rate' => 1.000000,
            'is_default' => true
        ]);

        $default = $this->currencyService->getDefaultCurrency();

        $this->assertNotNull($default);
        $this->assertEquals('MMK', $default->code);
        $this->assertTrue($default->is_default);
    }

    public function test_can_convert_between_currencies()
    {
        Currency::create([
            'code' => 'MMK',
            'name' => 'Myanmar Kyat',
            'symbol' => 'K',
            'exchange_rate' => 1.000000,
            'is_default' => true
        ]);

        Currency::create([
            'code' => 'USD',
            'name' => 'US Dollar',
            'symbol' => '$',
            'exchange_rate' => 0.000476
        ]);

        // Convert 1000 MMK to USD
        $usd = $this->currencyService->convert(1000, 'MMK', 'USD');

        $this->assertEqualsWithDelta(0.476, $usd, 0.01);
    }

    public function test_convert_same_currency_returns_same_amount()
    {
        Currency::create([
            'code' => 'USD',
            'name' => 'US Dollar',
            'symbol' => '$',
            'exchange_rate' => 1.000000
        ]);

        $amount = $this->currencyService->convert(100, 'USD', 'USD');

        $this->assertEquals(100, $amount);
    }

    public function test_can_format_currency()
    {
        Currency::create([
            'code' => 'USD',
            'name' => 'US Dollar',
            'symbol' => '$',
            'exchange_rate' => 1.000000,
            'decimal_places' => 2
        ]);

        $formatted = $this->currencyService->format(1234.56, 'USD');

        $this->assertStringContainsString('$', $formatted);
        $this->assertStringContainsString('1,234.56', $formatted);
    }

    public function test_can_initialize_default_currencies()
    {
        $count = $this->currencyService->initializeDefaultCurrencies();

        $this->assertGreaterThan(0, $count);
        $this->assertDatabaseHas('currencies', ['code' => 'MMK']);
        $this->assertDatabaseHas('currencies', ['code' => 'USD']);
        $this->assertDatabaseHas('currencies', ['code' => 'EUR']);
    }

    public function test_get_active_currencies()
    {
        Currency::factory()->create(['is_active' => true]);
        Currency::factory()->create(['is_active' => true]);
        Currency::factory()->create(['is_active' => false]);

        $active = $this->currencyService->getActiveCurrencies();

        $this->assertCount(2, $active);
    }

    public function test_can_update_exchange_rate_with_history()
    {
        $currency = Currency::create([
            'code' => 'USD',
            'name' => 'US Dollar',
            'symbol' => '$',
            'exchange_rate' => 1.000000
        ]);

        $currency->updateRate(1.050000);

        $this->assertEquals(1.050000, $currency->fresh()->exchange_rate);
        $this->assertDatabaseHas('exchange_rate_history', [
            'currency_id' => $currency->id,
            'rate' => 1.000000
        ]);
    }

    public function test_convert_throws_exception_for_invalid_currency()
    {
        $this->expectException(\Exception::class);
        
        $this->currencyService->convert(100, 'INVALID', 'MMK');
    }

    public function test_cached_rates_are_available()
    {
        Currency::factory()->count(3)->create(['is_active' => true]);

        $rates = $this->currencyService->getCachedRates();

        $this->assertIsArray($rates);
        $this->assertCount(3, $rates);
    }
}
