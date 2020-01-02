<?php

namespace App\Responses;

class UserResponse extends BaseResponse
{
    public function __construct(){
        parent::__construct();
    }

    public function listPartners($pageNumber, $itemsPerPage, $partners){
        $result = $this->paginate($pageNumber, $itemsPerPage, 'partners', $partners);
		return $this->successWithData('data_fetched', $result);
    }

}