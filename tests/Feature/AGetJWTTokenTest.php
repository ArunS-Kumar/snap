<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\JwtService;
use App\Models\{User,Role};

class AGetJWTTokenTest extends TestCase
{
    public static $adminToken;
    public static $partnerToken;

    public function testExample()
    {
        $this->artisan('migrate');
        $this->artisan('db:seed');

        $adminRoleId = Role::where('name','admin')->first()->id;
        $adminUser = factory(User::class)->create([
            'role_id' => $adminRoleId
        ]);
        self::$adminToken = (new JwtService())->generateTokenForUser($adminUser);

        $partnerRoleId = Role::where('name','partner')->first()->id;
        $partnerUser = factory(User::class)->create([
            'role_id' => $partnerRoleId
        ]);
        self::$partnerToken = (new JwtService())->generateTokenForUser($partnerUser);

        $this->assertInstanceOf(User::class, $adminUser);
        $this->assertInstanceOf(User::class, $partnerUser);
    }
}

