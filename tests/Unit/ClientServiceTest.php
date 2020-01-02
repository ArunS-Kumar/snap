<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\ClientService;
use DB;
use App\Models\Client;

class ClientServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetClientsByUserId()
    {
        $clientService = new ClientService;
        $searchString =  "";
        $sortBy = "id";
        $sortDir = "asc";
        $userId = DB::table('client_user')->select('user_id')->first()->user_id;
        $clientCountByUserId = DB::table('client_user')->where('user_id',$userId)->count();
        $clientData = $clientService->getClientsByUserId($userId, $searchString, $sortBy, $sortDir);
        $client = $clientData->first();
        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals($clientCountByUserId, count($clientData));
    }
}


