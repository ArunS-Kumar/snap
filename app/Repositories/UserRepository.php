<?php

namespace App\Repositories;

use App\Models\{User,UserSession,Client,PartnerCompany};
use App\Repositories\{ClientRepository,RoleRepository};

class UserRepository extends BaseRepository {

    public function __construct(){
        parent::__construct();
	}
	
	public function getPartnersByCompanyId($companyId){
		return User::whereHas('partnerCompanies', function($q) use($companyId){
			$q->where('partner_companies.id', $companyId);
		})
		->withCount('clients')
		->get();
	}
	
	public function getUserById($userId){
		return User::find($userId);
	}	
	
    public function createOrUpdate($userDetails,$role){
		$user = User::where('email', $userDetails->email)->first();
		if(!$user)
			$user = new User;

		$user->name = $userDetails->name;
		$user->email = $userDetails->email;
		$user->role_id = $role->id;
		$user->status = $userDetails->is_active; 
		$user->save();

		return $user;
	}

	public function createUserSession($accessToken, $refreshToken, $user, $jwtToken){
		$session = new UserSession;
		$session->user_id = $user->id;
		$session->jwt_token = $jwtToken;
		$session->access_token = $accessToken;
		$session->refresh_token = $refreshToken;
		$session->user_agent = $_SERVER['HTTP_USER_AGENT'];
		$session->ip_addr = $_SERVER['REMOTE_ADDR'];
		$session->save();
	}

	public function getUserSessionFromJwt($jwtToken){
		return UserSession::where('jwt_token',$jwtToken)->first();
	}
}