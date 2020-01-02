<?php

namespace App\Exceptions;

use Exception;
use App\Responses\BaseResponse;

class InactiveUserException extends Exception
{
    public function report(){
        \Log::error($this->getMessage());
        // $data = json_decode($this->getMessage());
        // \App\Services\LogDNAService::getInstance()->log(\Monolog\Logger::INFO, 'Comply', [
        //     'RequestType' => 'Exception',
        //     'ExceptionType' => 'InactiveUserException',
        //     'RequestURL' => url()->full(),
        //     'Response' => [
        //         'Message' => $data->message,
        //         'User' => (\Auth::user()) ? \Auth::user()->email : "N/A",
        //         'Method' => $data->method,
        //     ]
        // ]); 
    }

    public function render(){
        return (new BaseResponse())->error("user_inactive",401);
    }
}