<?php

namespace App\Exceptions;

use Exception;
use App\Responses\BaseResponse;

class InvalidIncomingDataException extends Exception
{
    public function report(){
        \Log::error("InvalidIncomingDataException");
        \log::error($this->getMessage());
    }

    public function render(){
        return (new BaseResponse())->error("invalid_type");
    }
}
