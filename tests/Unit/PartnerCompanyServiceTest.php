<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\PartnerCompService;
use App\Models\PartnerCompany;

class PartnerCompanyServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetPartnerCompaniesList()
    {
        $companyListTotal = PartnerCompany::count();
        $companyList = (new PartnerCompService)->getPartnerCompaniesList();
        $this->assertEquals($companyListTotal, count($companyList));
    }
}
