<?php

namespace App\Exceptions;

use Exception;
use App\Responses\BaseResponse;

class InvalidSignaturesOnIncomingCall extends Exception
{
    public function report(){
        \Log::error("InvalidSignaturesOnIncomingCall:".$this->getMessage());
        \Log::error("Request: ".json_encode(request()->all()));
        \Log::error("User agent: ".json_encode(request()->header('User-Agent')));
        \Log::error("IP: ".json_encode(request()->ip()));  
    }

    public function render(){
        return (new BaseResponse)->error("failed",400);
    }
}