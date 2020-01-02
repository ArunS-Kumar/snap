<?php

namespace App\Exceptions;

use Exception;
use App\Responses\BasesResponse;

class UserCreateOrUpdateFailed extends Exception
{
    public function report(){
        \Log::error($this->getMessage());
        $data = json_decode($this->getMessage());
        \App\Services\LogDNAService::getInstance()->log(\Monolog\Logger::INFO, 'Snapshot', [
            'RequestType' => 'Exception',
            'ExceptionType' => 'UserCreateOrUpdateFailed',
            'RequestURL' => url()->full(),
            'Response' => [
                'Message' => $data->message,
                'User' => (\Auth::user()) ? \Auth::user()->email : "N/A",
                'Details' => $data->details,
                'Role' => $data->role,
                'Method' => $data->method,
            ]
        ]); 
    }

    public function render(){
        return (new BaseResponse())->error("auth_exception",500);
    }
}
