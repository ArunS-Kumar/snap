<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PartnerCompService;
use App\Responses\PartnerCompanyResponse;
use App\Http\Requests\{GetPartnerCompanysListRequest,GetCompanyDetailsRequest};

class PartnerCompanyController extends Controller
{
    public $partnerCompService = null;
    public $partnerCompResponse = null;

    public function __construct()
    {
        $this->partnerCompService = new PartnerCompService;
        $this->partnerCompResponse = new PartnerCompanyResponse;
    }

    public function getPartnerCompaniesList(GetPartnerCompanysListRequest $req)
    {
        $perPage = $req->has('per_page') ? $req->per_page: (Config('custom.per_page'));
        $pageNum = $req->has('page') ? $req->page : 1;
        $companies = $this->partnerCompService->getPartnerCompaniesList();
        return $this->partnerCompResponse->listCompanies($pageNum, $perPage, $companies);
    }

    public function findById(GetCompanyDetailsRequest $req, $companyId)
    {
        $company = $this->partnerCompService->findById($companyId);
        return $this->partnerCompResponse->successWithData("data_fetched", $company);
    }    
}
