<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\UserService;
use App\Models\{User,PartnerCompany};
use DB;

class UserServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetUser()
    {
        $userService = new UserService;
        $user = User::inRandomOrder()->first();
        $returnUser = $userService->getUserById($user->id);
        $this->assertInstanceOf(User::class, $returnUser);
    }

    public function testGetPartnersByCompanyId()
    {
        $userService = new UserService;
        $companyId = DB::table('partner_company_user')->select('partner_company_id')->first()->partner_company_id;
        $userCountByCompanyId = DB::table('partner_company_user')->where('partner_company_id',$companyId)->count();

        $userData = $userService->getPartnersByCompanyId($companyId);
        $user = $userData->first();
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userCountByCompanyId, count($userData));
    }


}
