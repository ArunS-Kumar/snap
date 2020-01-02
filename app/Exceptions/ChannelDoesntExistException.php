<?php

namespace App\Exceptions;

use Exception;
use App\Responses\BaseResponse;

class ChannelDoesntExistException extends Exception
{
    public function report(){
        \Log::error($this->getMessage());
    }

    public function render(){
        return (new BaseResponse())->error("Invalid product id", 400);
    }
}
