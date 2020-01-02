<?php
namespace App\Services;

use App\Repositories\ClientRepository;

class ClientService extends BaseService
{
    public $clientRepo = null;

    public function __construct()
    {
        $this->clientRepo = new ClientRepository;
    }
    
    public function getClientsByUserId($userId, $searchString, $sortBy, $sortDir)
    {
        return $this->clientRepo->getClientsByUserId($userId, $searchString, $sortBy, $sortDir);
    }
}