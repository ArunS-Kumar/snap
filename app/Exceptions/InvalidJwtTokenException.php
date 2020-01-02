<?php

namespace App\Exceptions;

use Exception;
use App\Responses\AuthResponse;

class InvalidJwtTokenException extends Exception
{
    public function report(){
        $data = json_decode($this->getMessage());
        \Log::error($this->getMessage());
    }

    public function render(){
        return (new AuthResponse())->error("invalid_token",401);
    }
}
