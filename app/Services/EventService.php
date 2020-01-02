<?php

namespace App\Services;

use App\Repositories\ClientRepository;

class EventService extends BaseService
{
    public $clientRepo = null;
    protected $baseUrl;

    public function __construct()
    {
        $this->clientRepo = new ClientRepository;
        $this->baseUrl = Config('custom.events_api_base_url');
    }

    public function getEvents($userId, $eventType, $page, $per_page)
    {
        // Invoke the event api from EVENT APP
        $crmIds = $this->clientRepo->getClientCrmIdsBytheUserId($userId);
        $headers['Content-Type'] = "application/json";
        $params = ['client_crm_ids' => $crmIds,
                    'event_type' => $eventType,
                    'page' =>  $page,
                    'per_page' => $per_page      
                ];

        $url = $this->baseUrl . "events/all";
        try {
			$response = $this->sendRequest('POST', $url, $params, $headers);
			if (empty($response['result'])) {
				\Log::error("------------No Data from EVENT SERVICE-----------");
				\Log::error("params: ".json_encode($params));
				\Log::error("URL: ".$url);
				\Log::error("---------------------------------------");
				return [];
            } 
            return $response['result']['events'];
		} catch (\Exception $e) {
            \Log::error("------------Unable to connect EVENT SERVICE-----------");
            \Log::error("URL: ".$url);
			\Log::error("error : ".json_encode([ 'message' => $e->getMessage(),'headers' => $headers, 'params' => $params]));
            \Log::error("---------------------------------------");
        }
    }

	protected function sendRequest($method, $url, $params=[], $headers=[]){
		//manually required
		$headers['Content-Type']="application/json";
		$client = new \GuzzleHttp\Client();
		$response = $client->request($method, $url, [
			'body'=>json_encode($params),
			'headers'=>$headers
        ]);
        return json_decode($response->getBody(), true);
    }
}