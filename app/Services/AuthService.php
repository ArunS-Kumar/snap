<?php

namespace App\Services;

use App\Exceptions\{InvalidResponseFromAuthException,NoAccessToProductException,RoleDoesntExistException};
use App\Repositories\{RoleRepository,UserRepository,ClientRepository};
use Validator;

class AuthService extends BaseService {

    protected $httpClient;
    public function __construct() {
        parent::__construct();
        $this->httpClient = new \GuzzleHttp\Client;
	}
	

	public function getAccessTokenFromAuthByCode($code){
		 try {
	        $response = $this->httpClient->post(config('auth.oauth.auth_url').'/oauth/token', [
	            'form_params' => [
	                'grant_type' => 'authorization_code',
	                'client_id' => config('auth.oauth.client_id'),
	                'client_secret' => config('auth.oauth.client_secret'),
	                'redirect_uri' => config('auth.oauth.redirect_uri'),
	                'code' => $code,
	            ],
			]);
			
			$response = json_decode((string)$response->getBody());
			if(!isset($response->access_token))
				throw new InvalidResponseFromAuthException(json_encode(["message" => "Access token not received from auth!", "code" => $code, "method" => __METHOD__]));
	        return $response;
		}
		catch(InvalidResponseFromAuthException $e){
			throw new InvalidResponseFromAuthException($e->getMessage());
		}
        catch (\Exception $e) {
            throw new InvalidResponseFromAuthException(json_encode(["message" => $e->getMessage(),  "code" => $code, "method" => __METHOD__]));
        }
	}

	public function getUserInfo($accessToken){
		try {
			$response = $this->httpClient->get(config('auth.oauth.auth_url').'/api/get-user-info', [
	            'headers' => [
	                'Accept' => 'application/json',
	                'Authorization' => 'Bearer '.$accessToken,
	                'ClientId' => config('auth.oauth.client_id'),
	            ],
			]);

			$result = json_decode((string) $response->getBody());
			if(isset($result->code) && ($result->code == 402 || $result->code == 'invalid_product_access')) {
				if($result->code == 'invalid_product_access')
					$message = 'No access to product!';
				else 
					$message = $result->message;
					
				throw new NoAccessToProductException(json_encode(["message" => $message,  "token" => $accessToken, "method" => __METHOD__]));
			}

	        return $result;
        }
        catch (\Exception $e) {
			throw new NoAccessToProductException(json_encode(["message" => $e->getMessage(),  "token" => $accessToken, "method" => __METHOD__]));
        }
    }
    
    public function syncUserDetails($userDetails){
		$roleRepo = new RoleRepository();

		$role = $roleRepo->getRoleByName($userDetails->role);
    	if(!$role) {
    		throw new RoleDoesntExistException(json_encode(["message" => "The role:".$userDetails->role." doesn't exist!", "user_details" => json_encode($userDetails), "method" => __METHOD__]));
    	}

		try{
			$user = (new UserRepository())->createOrUpdate($userDetails,$role);
		}
		catch(\Exception $e){
			throw new UserCreateOrUpdateFailed(json_encode(["message" => $e->getMessage(),  "details" => json_encode($userDetails),  "role" => $role->name, "method" => __METHOD__]));
		}

		try{
			if(isset($userDetails->clients) && is_array($userDetails->clients))
				$this->syncClients($userDetails,$user,$role);
		}
		catch(\Exception $e){
			//we only log here, because we don't want the flow of login to be stopped!
			\Log::error("Client syncing failed for the user: ".$user->email." and the details:".json_encode($userDetails)."\nException msg:".$e->getMessage());
		}
		
		return compact('user','role');
	}

	public function syncClients($userDetails,$user,$role){
		$ids=[];
		foreach ($userDetails->clients as $clientDetails){

			$v = Validator::make((array)$clientDetails,[
				'name'=>'required',
				'crm_id'=>'required|integer',
				'status'=>'required|in:active,deactive',
				'is_prospect'=>'required|boolean'
			]);
			if($v->fails()){
				\Log::error("Not migrating a client from auth to Snapshot, because of failed validation, reasons: ".json_encode($v->messages()));
				\Log::error("Client Details: ".json_encode($clientDetails));
				continue;
			}
			$client = (new ClientRepository())->createOrUpdate($clientDetails);

			//we don't sync here, so that if its an admin, we don't need to sync at all!
			array_push($ids,$client->id);
		}

		//sync makes sure that only these ids stay in the pivot table
		//if we only used attach, then the clients who were previously there, will still be there
		//and we don't want that behaviour
		if($role['name'] != 'admin')
			$user->clients()->sync($ids);
		
		return true;
	}

}