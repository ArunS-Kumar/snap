<?php

namespace App\Responses;

class ClientResponse extends BaseResponse
{
    public function __construct(){
        parent::__construct();
    }

    public function listClients($pageNumber, $itemsPerPage, $clients){
        $result = $this->paginate($pageNumber, $itemsPerPage, 'clients', $clients);
		return $this->successWithData('data_fetched', $result);
    }
}