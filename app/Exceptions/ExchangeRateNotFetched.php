<?php

namespace App\Exceptions;

use App\Responses\BaseResponse;

use Exception;

class ExchangeRateNotFetched extends Exception
{
    public function report()
    {
        \Log::error($this->getMessage());
        \App\Services\LogDNAService::getInstance()->log(\Monolog\Logger::INFO, 'Snapshot', [
            'RequestType' => 'Exception',
            'ExceptionType' => str_replace("App\Exceptions\\", '', get_class($this)),
            'RequestURL' => url()->full(),
            'RequestMethod' => $_SERVER['REQUEST_METHOD'],
            'Response' => [
                'message' => $this->getMessage(),
                'user' => (\Auth::user()) ? \Auth::user()->email : "N/A"
            ]
        ]);
    }

    public function render()
    {
        return (new BaseResponse())->error("no_data",500);
    }
}

