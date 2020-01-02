<?php

namespace App\Exceptions;

use Exception;
use App\Responses\BaseResponse;

class OtsException extends Exception
{
    //send the params to a service or log them
    public function report(){
        \Log::error("---------Ots Exception--------");
        \Log::error($this->getMessage());
        \Log::error(json_encode(request()->all()));
        \Log::error(json_encode(request()->url()));
        \Log::error("------------------------------");

        $expData = json_decode($this->getMessage());
        // \App\Services\LogDNAService::getInstance()->log(\Monolog\Logger::INFO, 'Analytics2.0', [
        //     'RequestType' => 'Exception',
        //     'ExceptionType' => str_replace("App\Exceptions\\", '', get_class($this)),
        //     'RequestURL' => url()->full(),
        //     'RequestMethod' => $_SERVER['REQUEST_METHOD'],
        //     'Response' => [
        //         'message' => $expData->message,
        //         'user' => (\Auth::user()) ? \Auth::user()->email : "N/A",
        //         'headers' => $expData->headers,
        //         'params' => json_encode($expData->params)
        //     ]
        // ]);
    }

    public function render($request){
        return (new BaseResponse())->errorWithMessage("exception","Sorry, but the server encountered an error! Please try again!",500);
    }
}
