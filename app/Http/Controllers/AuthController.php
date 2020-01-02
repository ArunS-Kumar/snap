<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GetTokenRequest;
use App\Services\{AuthService,JwtService};
use App\Responses\AuthResponse;

class AuthController extends Controller
{
    // gets the access and refresh token from Auth using code
    public function getToken(GetTokenRequest $req)
    {
        $authService = new AuthService();
        $jwtService = new JwtService();
        $authResponse = new AuthResponse();
        // Get Access token from Auth
        $authResult = $authService->getAccessTokenFromAuthByCode($req->code);
        //Get User info from Auth
        $userDetails = $authService->getUserInfo($authResult->access_token);
        // Sync User Details with DB
        $userAndRole = $authService->syncUserDetails($userDetails);
        $user = $userAndRole['user'];

        $company = [];
        $company = $user->partnerCompanies()->select('logo','name')->get()->first();
        // // Generate Jwt Token
        $jwtToken = $jwtService->createTokenWithSession($authResult->access_token, $authResult->refresh_token , $user);
        return $authResponse->login($jwtToken, $user, $company);
    }
}
