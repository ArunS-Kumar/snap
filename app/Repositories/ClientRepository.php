<?php

namespace App\Repositories;

use App\Models\Client;
use App\Events\SyncEvent;

class ClientRepository extends BaseRepository {

    public function __construct(){
        parent::__construct();
    }
    
    public function getClientByCrmId($clientCrmId){
		return Client::where('crm_id', $clientCrmId)->first();
    }

    public function createOrUpdate($details){
        try{
            $client = Client::where('crm_id', $details->crm_id)->first();
            if(!$client){
                $client = new Client;
            }
            $client->name = $details->name;
            $client->crm_id = $details->crm_id;
            $client->logo_url = $details->logoUrl;
            $client->is_prospect = $details->is_prospect;
            $client->status = $details->status=="deactive"?"inactive":$details->status; //this is because we have used 'inactive' in snapshot while 'deactive' in Auth, but deactive doesn't sound right!
            $client->save();

            $client->source = 'snapshot';
            event(new SyncEvent('sync_single_client_create', $client));
        }catch(\Exception $e){
            \Log::error("something went wrong: ".$e->getMessage());
            return false;
        }
		return $client;
    }
    
    public function getClientsByUserId($userId, $searchString, $sortBy, $sortDir){	
		$clients = Client::whereHas('users', function($q) use($userId) {
			$q->where('users.id', $userId);
		})
		->client()
        ->where(function($wquery) use ($searchString)  {
            if (!empty($searchString)) {
                $wquery->where('name', 'ilike', '%' . $searchString. '%');
            }
        })
        ->orderBy($sortBy, $sortDir)
        ->get();

		if($clients->isEmpty()){
			return [];
		}
		return $clients;
    }


    public function getClientCrmIdsBytheUserId($userId){
        $crmIds = Client::whereHas('users', function($q) use($userId){
            $q->where('users.id', $userId);
        })  
        ->client()
        ->pluck('crm_id');

		if($crmIds->isEmpty()){
			return [];
		}
		return $crmIds;
    }

}