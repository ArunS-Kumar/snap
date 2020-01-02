<?php

namespace App\Responses;

class PartnerCompanyResponse extends BaseResponse
{
    public function __construct(){
        parent::__construct();
    }

    public function listCompanies($pageNumber, $itemsPerPage, $companies){
        $result = $this->paginate($pageNumber, $itemsPerPage, 'companies', $companies);
		return $this->successWithData('data_fetched', $result);
    }

}