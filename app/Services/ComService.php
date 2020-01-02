<?php

namespace App\Services;

use Carbon\Carbon;
use App\Exceptions\{InvalidIncomingDataException,ChannelDoesntExistException,OutgoingJobFailedException};
use App\Repositories\ComRepository;
use App\Repositories\{RoleRepository, UserRepository, ClientRepository, PartnerCompanyRepository};

class ComService extends BaseService{
	
	private $channel;

	public function __construct(){}

	public function verifySignatures($sg1,$sg2){
		$secret = config('app.coms_key');
		$expectedSg1 = hash("sha256",$secret."_".$sg2);
		if($expectedSg1 !== $sg1){
			return false;
		}

		//check if the time is valid
		$sentOn = Carbon::createFromTimestamp($sg2);
		$diff = Carbon::now()->diffInSeconds($sentOn,false);
		if($diff>0 || abs($diff)>intval(config('custom.coms_validity_in_seconds'))){
			return false;
		}
		return true;
	}

	public function syncIncomingData($data){
		switch($data['type']){
			case 'sync_partner_user_clients':
                $userData = json_decode(json_encode($data['user']));
                $roleLabel = $userData->role;
                $role = (new RoleRepository())->getRoleByName($roleLabel);

				if(!$role) {
					\Log::error("The role:".$roleLabel." doesn't exist!");
					return response()->json(['code'=>'failed']);
				}

				$user = (new UserRepository())->createOrUpdate($userData, $role);
				if(!$user)
					return response()->json(['code'=>'failed']);

                $ids = [];
                foreach($userData->clients as $client){
                    if(empty($client->crm_id)){
                            \Log::error("skipping syncing of user: ".$user->id." because there is no crm id exist");
                            continue;
                    }
                    $newClient = (new ClientRepository())->createOrUpdate($client);
                    if(!$newClient){
                        \Log::error("skipping syncing of user: ".$user->id." with the client: ".$client->crm_id." because of some DB entry issue");
                        continue;
                    }
                    array_push($ids,$newClient->id);
                }
				$user->clients()->sync($ids);


				$comp_ids = [];
                foreach($userData->partner_companies as $company){
                    if(empty($company->unique_id)){
                            \Log::error("skipping syncing of company: ".$user->id." because there is no unique id exist");
                            continue;
                    }
                    $newCompany = (new PartnerCompanyRepository())->createOrUpdate($company);
                    if(!$newCompany){
                        \Log::error("skipping syncing of company: ".$user->id." with the company: ".$company->unique_id." because of some DB entry issue");
                        continue;
                    }
                    array_push($comp_ids,$newCompany->id);
                }
				$user->partnerCompanies()->sync($comp_ids);
			break;

			case 'sync_partner_company':
				$company = json_decode(json_encode($data['company']));
				if(! ((new PartnerCompanyRepository())->createOrUpdate($company)) ) {
					\Log::error("skipping syncing of company: ".$company->unique_id." because of some DB entry issue");
					continue;
				}
			break;

			default:
			throw new InvalidIncomingDataException(json_encode(['msg'=>"type of data not supported!","data"=>$data]));
		}

		return response()->json(['code'=>'success']);
	}

	
	public function sendData($data=[]){
		$this->channel = (new ComRepository())->getChannel();
		if(!$this->channel)
			throw new ChannelDoesntExistException("Invalid product for the channel");

		\Log::error("sync job data:");
		\Log::error(json_encode($data));

		$apiUrl = $this->channel->outgoing_url;
		$signatureParams = $this->getSignatureParams();
		$params = array_merge($signatureParams,compact('data'));

		try{
			$response=$this->sendRequest("POST",$apiUrl,$params);
		}
		catch(\GuzzleHttp\Exception\ClientException $e){
			$response = $e->getResponse()->getBody(true)->getContents();

			//handle failures
			$responseArr=json_decode($response,true);
			if($responseArr['code']=="failed"){
				$msg = "failed while syncing";
			}
			else if($responseArr['code']=="invalid_type"){
				$msg = "Type of data not supported!";
			}
			else{
				$msg = $e->getMessage();
			}

			throw new OutgoingJobFailedException(json_encode(['msg'=>$msg,'response'=>$response,'data'=>$data]));
		}
		
		//handle success
		if($response['code']=="success"){
			echo "success";
		}
	}

	protected function sendRequest($method,$url,$params=[],$headers=[],$is_binary=false){
		//manually required
		$headers['Content-Type']="application/json";
		$client = new \GuzzleHttp\Client();
		$response=$client->request($method, $url, [
			'body'=>json_encode($params),
			'headers'=>$headers
		]);

		if(!$is_binary)
			$result = json_decode($response->getBody(), true);
		else
			$result = $response->getBody()->getContents();
		
		return $result;
	}

	private function getSignatureParams(){
		$sg2 = time();
		$sg1 = $this->channel->secret."_".$sg2;
		$sg1 = hash("sha256",$sg1);
		return compact('sg1','sg2');
	}
}
