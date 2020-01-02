<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\AGetJWTTokenTest as Tuser;

class ClientsAPITest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testVATAnalysis()
    {
        $toDate = date("Y-m-d");
        $from = date("Y-m-d",strtotime("-1 year"));

        // get crm ids from .env.testing 
        $crmIds = explode(",", env('CRMIDS'));

        //pick some random crmid
        $crmid = $crmIds[array_rand($crmIds, 1)];
        $url = "api/clients/$crmid/vat-analysis/data";
        $response = $this->withHeaders([
            'Authorization' => "Bearer ".Tuser::$adminToken])
        ->post($url,array (
            'more_filters' => 
            array (
              'SentForPaymentTo' => 
              array (
                'type' => 'Date',
                'value' => $toDate.' 23:59:59',
              ),
              'SentForPaymentFrom' => 
              array (
                'type' => 'Date',
                'value' => $from.' 00:00:00',
              ),
            ),
          ));

        $response
            ->assertStatus(200)
            ->assertJson([
                'code' => 'data_fetched'
            ]);
    }
}
