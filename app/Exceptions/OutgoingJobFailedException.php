<?php

namespace App\Exceptions;

use Exception;

class OutgoingJobFailedException extends Exception
{
    public function report(){
        $expData=json_decode($this->getMessage(),true);
        \Log::error("msg:".$expData['msg']);
        \Log::error("response:".$expData['response']);
        \Log::error("data:".json_encode($expData['data']));
    }

    public function render(){
        echo "exception";
    }
}
