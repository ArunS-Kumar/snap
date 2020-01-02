<?php

namespace App\Services;
use JWTAuth;
use JWT;
use App\Repositories\UserRepository;
use App\Exceptions\InvalidJwtTokenException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Carbon\Carbon;

class JwtService extends BaseService {

    public function __construct() {
        parent::__construct();
	}
	

    public function generateTokenForUser($user){
		return JWTAuth::fromUser($user);
	}

	public function createTokenWithSession($accessToken,$refreshToken, $user){
		$token = $this->generateTokenForUser($user);
		(new UserRepository())->createUserSession($accessToken, $refreshToken, $user, $token);
		return $token;
	}

	public function decodeToken(){
		try{
			//automatically gets the token from the request
			$user = JWTAuth::parseToken()->toUser();
			return ['status'=>'unchanged','token'=>JWTAuth::getToken(),'user_id'=>$user->id];
		}
		catch(TokenExpiredException $e){
			$token = JWTAuth::getToken();
			// $authService = new AuthService();
			// dd($token);
			$userSession=(new UserRepository())->getUserSessionFromJwt($token);
			if(!$userSession)
				throw new InvalidJwtTokenException("User session not found!");
			//this is the first API that has presented us with an expired token
			//grace_time implementation
			if(is_null($userSession->valid_till)){
				$userSession->valid_till = Carbon::now()->addSeconds(config('jwt.grace_time'));
				$userSession->save();
			}
			//this is not the first API, check if the token's grace time has passed or not
			else if(Carbon::now()->gt(Carbon::parse($userSession->valid_till))){
				throw new InvalidJwtTokenException("Token expired and grace time over");
			}
			else{
				return ['status'=>'unchanged','token'=>$token,'user_id'=>$userSession->user_id];
			}

			// $authResult = $authService->getUserInfo($userSession->access_token);
			//If Access token is not valid generating refresh token from auth
			// if(!is_object($authResult) && isset($authResult['code']) && $authResult['code'] == 401){	
			// 	$userSession = $authService->RefreshTokenFromAccessToken($userSession->refresh_token);
			// 	$authResult = $authService->getUserInfo($userSession->access_token);
			// }
			// dd($authResult);


			$user=(new UserRepository())->getUserById($userSession->user_id);

			
			// Generating New Jwt Token
			//and adding a userSession
			$newJwtToken = $this->createTokenWithSession($userSession->access_token,$userSession->refresh_token,$user);

			return ['status'=>'changed','token'=>$newJwtToken,'user_id'=>$user->id];

		}
        catch(\Exception $e){
            throw new InvalidJwtTokenException($e->getMessage());
        }
	}
	


}