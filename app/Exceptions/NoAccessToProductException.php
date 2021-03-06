<?php

namespace App\Exceptions;

use Exception;
use App\Responses\BaseResponse;

class NoAccessToProductException extends Exception
{
    public function report(){
        \Log::error($this->getMessage());

        $data = json_decode($this->getMessage());
        \App\Services\LogDNAService::getInstance()->log(\Monolog\Logger::INFO, 'Snapshot', [
            'RequestType' => 'Exception',
            'ExceptionType' => 'NoAccessToProductException',
            'RequestURL' => url()->full(),
            'Response' => [
                'Message' => $data->message,
                'User' => (\Auth::user()) ? \Auth::user()->email : "N/A",
                'Token' => $data->token,
                'Method' => $data->method,
            ]
        ]); 
    }

    public function render(){
        return (new BaseResponse())->error("permission_denied",403);
    }
}
