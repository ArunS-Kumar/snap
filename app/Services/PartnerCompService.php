<?php
namespace App\Services;

use App\Repositories\PartnerCompanyRepository;

class PartnerCompService extends BaseService
{ 
    public $partnerCompRepo = null;

    public function __construct()
    {
        $this->partnerCompRepo = new PartnerCompanyRepository;
    }
    
    public function findById($companyId)
    {
        return $this->partnerCompRepo->findById($companyId);
    }
    
    public function getPartnerCompaniesList()
    {
        return $this->partnerCompRepo->getPartnerCompaniesList();
    }
}