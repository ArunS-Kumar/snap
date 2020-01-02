<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\InvalidSignaturesOnIncomingCall;
use App\Services\ComService;

class VerifyIncomingCall
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
        if(!($request->has('sg1') && $request->has('sg2'))){
            throw new InvalidSignaturesOnIncomingCall("The signatures are missing!");
        }
        if(!(new ComService())->verifySignatures($request->sg1,$request->sg2)){
            throw new InvalidSignaturesOnIncomingCall("Incorrect secret!");
        }
        return $next($request);
    }
}