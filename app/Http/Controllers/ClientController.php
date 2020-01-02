<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ClientService;
use App\Responses\ClientResponse;
use App\Http\Requests\{GetClientsListRequest};

class ClientController extends Controller
{
    public $clientService = null;
    public $clientResponse = null;

    public function __construct()
    {
        $this->clientService = new ClientService;
        $this->clientResponse = new ClientResponse;
    }

    public function getClientsByUser(GetClientsListRequest $req, $userId)
    {
        $perPage = $req->has('per_page') ? $req->per_page: (Config('custom.per_page'));
        $pageNum = $req->has('page') ? $req->page : 1;
        $searchString = $req->has('search') ? $req->search : "";
        $sortBy = $req->has('sort_by') ? $req->sort_by : "id";
        $sortDir = $req->has('sort_dir') ? $req->sort_dir : "asc";
        $clients = $this->clientService->getClientsByUserId($userId, $searchString, $sortBy, $sortDir);
        return $this->clientResponse->listClients($pageNum, $perPage, $clients);
    }
}
