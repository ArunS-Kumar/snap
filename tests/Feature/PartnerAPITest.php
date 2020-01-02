<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\JwtService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\AGetJWTTokenTest as Tuser;
use App\Models\{User,Role,Client,PartnerCompany};
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartnerAPITest extends TestCase
{

    public function testClientsList()
    {

        /* feed some data before we start test
          * create a new user
          * create few clients with proper crm id
          * attach those clients for the new user
          */
        $crmIds = explode(",", env('CRMIDS'));
        $count = count($crmIds);

        $partnerRoleId = Role::where('name','partner')->first()->id;
        $testUser = factory(User::class)->create(
            ['role_id' => $partnerRoleId]
        );

        for ($i=0; $i<$count; $i++) {
           $client = factory(Client::class)->create(
                ['crm_id' => $crmIds[$i]]
            );
            $testUser->clients()->attach($client->id);
        }
       /* feeding test data is done */


        /* 
        * case 1 : Access the api with the default partner token
        * result should be permission denied as the defatult partner trying to access the new user data
        * 
        */
        $url =  "api/partners/". $testUser->id. "/clients";
        $response1 = $this->withHeaders([
            'Authorization' => "Bearer ". Tuser::$partnerToken])
        ->get($url);
        $response1
            ->assertStatus(403)
            ->assertJson([
                'code' => 'permission_denied'
            ]);
        /* default partner user token test ends */

        /* 
        * case 2 : Access the api with the new partner token
        * Result should be fine and count also should match exactly
        * 
        */
        $testUserToken = (new JwtService())->generateTokenForUser($testUser);
        $response2 = $this->withHeaders([
            'Authorization' => "Bearer ".$testUserToken])
        ->get($url);
        $response2
            ->assertStatus(200)
            ->assertJson([
                'code' => 'data_fetched'
            ]);
 
        $clientCount = $testUser->clients()->count(); // Get actual client count for the new user
        $this->assertEquals($clientCount,
            collect($response2->decodeResponseJson('result.clients.data'))->count()
        );
        /* new partner user token test ends */

        /* 
        * case 3 : Access the api with the new partner token 
        * Pagination test
        * Result should be fine and count also should match exactly
        * 
        */
        $perPage = 2;
        $urlWithPagination =  "api/partners/". $testUser->id. "/clients?per_page=". $perPage ."&page=1";
        $response3 = $this->withHeaders([
            'Authorization' => "Bearer ".$testUserToken])
        ->get($urlWithPagination);

        $response3
            ->assertStatus(200)
            ->assertJson([
                'code' => 'data_fetched'
            ]);

        $this->assertEquals($perPage,
            collect($response3->decodeResponseJson('result.clients.data'))->count()
        );
        /* new partner user token pagination test ends */

        /* 
        * case 4 : Access the api with the Admin token 
        * Result should be fine 
        */
        $response4 = $this->withHeaders([
            'Authorization' => "Bearer ".Tuser::$adminToken])
        ->get($url);

        $response4
            ->assertStatus(200)
            ->assertJson([
                'code' => 'data_fetched'
            ]);   
        /* Admin token test ends */

        /* 
        * case 5 : Access the api with the Admin token 
        * Pagination test
        * Result should be fine and count also should match exactly
        */
        $response5 = $this->withHeaders([
            'Authorization' => "Bearer ".Tuser::$adminToken])
        ->get($urlWithPagination);

        $response5
            ->assertStatus(200)
            ->assertJson([
                'code' => 'data_fetched'
            ]);   

        $this->assertEquals($perPage,
            collect($response5->decodeResponseJson('result.clients.data'))->count()
        );
        /* Admin token pagination test ends */
    }

    public function testGetPartnerCompanies()
    {

        /* feed some data before we start test
        * create one new partner user
        * create some partner companies with unique id
        * attach those companies for the new user
        */

        $partnerRoleId = Role::where('name','partner')->first()->id;
        $companyUser = factory(User::class)->create(
            ['role_id' => $partnerRoleId]
        );

        $totalCompany = 5;
        for ($i=0; $i<$totalCompany; $i++) {
           $companies = factory(PartnerCompany::class)->create(
                ['unique_id' => mt_rand(1, 99999)]
            );
            $companyUser->partnerCompanies()->attach($companies->id);
        }

        /* 
        * case 1 : Access the api with the Admin token 
        * Result should be fine 
        * Data count should match
        */
        $companyUrl = 'api/partner/companies';
        $response = $this->withHeaders([
            "Authorization" => "Bearer ".TUser::$adminToken
        ])->get($companyUrl);

        $response
            ->assertStatus(200)
            ->assertJson(['code' => 'data_fetched']);

        $this->assertEquals($totalCompany,
            collect($response->decodeResponseJson('result.companies.data'))->count()
        );
        /* Admin token test ends here */

        /* 
        * case 2 : Access the api with the Admin token 
        * Result should be fine 
        * Pagination test
        */
        $companyPaginationUrl = $companyUrl . "?page=1&per_page=4";
        $response2 = $this->withHeaders([
            "Authorization" => "Bearer ".TUser::$adminToken
        ])->get($companyPaginationUrl);

        $response2
            ->assertStatus(200)
            ->assertJson(['code' => 'data_fetched']);

        $this->assertEquals(4,
            collect($response2->decodeResponseJson('result.companies.data'))->count()
        );
        /* Admin token pagination test ends here */

        /* 
        * case 3 : Access the api with the Partner token 
        * Result should be fine 
        */
        $response3 = $this->withHeaders([
            "Authorization" => "Bearer ".TUser::$partnerToken
        ])->get($companyUrl);

        $response3
            ->assertStatus(403)
            ->assertJson(['code' => 'permission_denied']);
        /* Partner token test ends here */

    }

    public function testGetPartnersListByCompanyId()
    {
        /* 
        * case 1 : Access the api with the Admin token 
        * Result should be fine 
        */
        $companyId = PartnerCompany::inRandomOrder()->first()->id;
        $url = "api/partners/list/".$companyId;
        $response = $this->withHeaders([
            "Authorization" => "Bearer ".TUser::$adminToken
        ])->get($url);

        $response
            ->assertStatus(200)
            ->assertJson(['code' => 'data_fetched']);

            $this->assertEquals(1,
                collect($response->decodeResponseJson('result.partners.data'))->count()
            );
        /* Admin token test ends here */

        /* 
        * case 2 : Access the api with the Admin token 
        * Result should be fine 
        * Pagination test
        */
        $comPaginationUrl2 = $url . "?page=1&per_page=1";

        $response2 = $this->withHeaders([
            "Authorization" => "Bearer ".TUser::$adminToken
        ])->get($comPaginationUrl2);

        $response2
            ->assertStatus(200)
            ->assertJson(['code' => 'data_fetched']);

            $this->assertEquals(1,
                collect($response2->decodeResponseJson('result.partners.data'))->count()
            );
        /* Admin token test ends here */

        /* 
        * case 3 : Access the api with the Partner token 
        * Result should be fine 
        */

        $response3 = $this->withHeaders([
            "Authorization" => "Bearer ".TUser::$partnerToken
        ])->get($url);

        $response3
            ->assertStatus(403)
            ->assertJson(['code' => 'permission_denied']);
        /* Partner token  test ends here */
    }

    public function testGetCompanyDetailsById()
    {

        /* 
        * case 1 : Access the api with the Admin token 
        * Result should be fine 
        */
        $companyId = PartnerCompany::inRandomOrder()->first()->id;
  
        $url = "api/partner-company/".$companyId;
        $response = $this->withHeaders([
            "Authorization" => "Bearer ".TUser::$adminToken
        ])->get($url);

        $response
            ->assertStatus(200)
            ->assertJson(['code' => 'data_fetched']);
        /* Admin token test is done */

        /* 
        * case 2 : Access the api with the Partner token 
        * Result should be fine 
        */

        $response = $this->withHeaders([
            "Authorization" => "Bearer ".TUser::$partnerToken
        ])->get($url);

        $response
            ->assertStatus(403)
            ->assertJson(['code' => 'permission_denied']);
        /* Partner token test is done */

    }



}
