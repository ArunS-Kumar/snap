<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\{JwtService,UserService};
use JWTAuth;
use App\Exceptions\InactiveUserException;

class TokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        $jwtService = new JwtService;

        $decodedData = $jwtService->decodeToken();

        $user = (new UserService())->getUserById($decodedData['user_id']);
        \Auth::login($user);

        if($user->status != 'active')
        {
            throw new InactiveUserException(json_encode(['message'=> 'Inactive User','method'=> __METHOD__]));
        }

        $response = $next($request);

        if($decodedData['status']=="changed"){
            $response->headers->set('Access-Control-Expose-Headers','X-NEW-TOKEN');
            $response->headers->set('X-NEW-TOKEN', $decodedData['token']);
        }

        return $response;

    }
}
