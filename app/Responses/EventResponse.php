<?php

namespace App\Responses;

class EventResponse extends BaseResponse
{
    public function __construct(){
        parent::__construct();
    }

    public function latestEvents($events){
        if (isset($events['data']) && count($events['data']) > 0) {
            $data = $events['data'];
            foreach($data as &$clientData){
                $clientData['client']['logo'] = \App\Models\Client::where('crm_id',$clientData['client']['crm_id'])->first()->logo_url;
            }
            $events['data'] = $data; 
            return $this->successWithData("data_fetched", ['events' => $events]);
        }
        else{
            return $this->successWithData("data_fetched", ['events' => ['data'=>[]]]);
        }
    }
}