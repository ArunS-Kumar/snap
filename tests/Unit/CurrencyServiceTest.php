<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\CurrencyService;
use App\Models\Currency;

class CurrencyServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $modelCount = Currency::count();
        $currencies = (new CurrencyService)->getCurrencies();
        $this->assertEquals(count($currencies), $modelCount);
    }
}
