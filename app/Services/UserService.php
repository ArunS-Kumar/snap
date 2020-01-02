<?php
namespace App\Services;

use App\Repositories\UserRepository;

class UserService extends BaseService
{
    public $clientRepo = null;

    public function __construct()
    {
        $this->userRepo = new UserRepository;
    }

    public function getPartnersByCompanyId($companyId)
    {
        return $this->userRepo->getPartnersByCompanyId($companyId);
    }

    public function getUserById($userId){
        return $this->userRepo->getUserById($userId);
    }
}