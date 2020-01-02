<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Responses\UserResponse;
use App\Http\Requests\{GetPartnersListRequest};

class UserController extends Controller
{
    public $userService = null;
    public $userResponse = null;

    public function __construct()
    {
        $this->userService = new UserService;
        $this->userResponse = new UserResponse;
    }

    public function getPartnersByCompanyId(GetPartnersListRequest $req, $companyId)
    {
        $perPage = $req->has('per_page') ? $req->per_page: (Config('custom.per_page'));
        $pageNum = $req->has('page') ? $req->page : 1;

        $partners = $this->userService->getPartnersByCompanyId($companyId);
        return $this->userResponse->listPartners($pageNum, $perPage, $partners);
    }

    
}
