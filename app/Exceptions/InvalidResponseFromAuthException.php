<?php

namespace App\Exceptions;

use Exception;
use App\Responses\AuthResponse;

class InvalidResponseFromAuthException extends Exception
{
    public function report(){
        \Log::error($this->getMessage());

        $data = json_decode($this->getMessage());
        \App\Services\LogDNAService::getInstance()->log(\Monolog\Logger::INFO, 'Snapshot', [
            'RequestType' => 'Exception',
            'ExceptionType' => 'InvalidResponseFromAuthException',
            'RequestURL' => url()->full(),
            'Response' => [
                'Message' => $data->message,
                'User' => (\Auth::user()) ? \Auth::user()->email : "N/A",
                'Code' => $data->code,
                'Method' => $data->method,
            ]
        ]); 
    }

    public function render(){
        return (new AuthResponse())->error("auth_exception",500);
    }
}
