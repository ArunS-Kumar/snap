<?php

namespace App\Exceptions;

use Exception;
use App\Responses\BaseResponse;

class ActionNotAllowedException extends Exception
{
    public function report(){
        \Log::error($this->getMessage());
    }

    public function render(){
        return (new BaseResponse())->error("action_not_allowed", 500);
    }
}
