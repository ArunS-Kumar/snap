<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\AGetJWTTokenTest as Tuser;
class CurrencyAPITest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCurrencyWithExchangeRate()
    {
        $url = 'api/currencies';
        $response = $this->withHeaders([
            'Authorization' => "Bearer ". TUser::$adminToken
        ])->get($url);

        $response
        ->assertStatus(200)
        ->assertJson(['code' => 'data_fetched']);
    }
}
