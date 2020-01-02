<?php

namespace App\Responses;

class AuthResponse extends BaseResponse
{
    public function __construct(){
        parent::__construct();
    }

    public function login($token, $user, $company){
		return $this->successWithData('logged_in',compact('token','user','company'));
	}

}