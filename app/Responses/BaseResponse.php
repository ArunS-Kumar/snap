<?php

namespace App\Responses;

class BaseResponse
{
    public function __construct(){
    }

    //simple indicators
    public function error($code="",$status=400){
        return response()->json([
            'code'=>$code
        ],$status);
    }

    //simple indicators
    public function success($code="",$status=200){
        return response()->json([
            'code'=>$code
        ],$status);
    }

    //a success response that also fetches some data for the frontend app
    public function successWithData($code="",$data,$status=200){
        return response()->json([
            'code'=>$code,
            'result'=>$data
        ],$status);
    }

    //specific case when the frontend needs to show a specific error message coming from the backend
    public function errorWithMessage($code = 'error_with_msg', $msg, $status=409){
        return response()->json([
            'code'=>$code,
            'msg'=>$msg
        ],$status);
    }

    //any validation errors in the application need to be sent in this format
    public function validationErrors($errors,$status=400){
        return response()->json([
            'errors'=>$errors,
            'code'=>'validation_errors'
        ],$status);
    }

    //used when the user's token is invalid or such conditions where the user needs to be logged in again
    public function relogin($status=401){
        return response()->json([
            'code'=>'relogin'
        ],$status);
    }

    //this is used only when A web route displays custom errors
    public function webError($msg){
        return response()->view('errors.webError',[
            'msg'=>$msg
        ]);
        }

        //custom pagination.
        //Used this instead of laravel pagination because relations do not contain complete data.
        //Example: $user->clients() is not always equal to the clients that are accessible by the user. Sometimes the user can access the clients (Client::all()). So, a relationship nesting can't be used. Therefore, paginate() can't be used.
    public function paginate($pageNumber, $itemsPerPage, $collectionkey, $collectionData){
        $paginatedArray = [
            'current_page' => $pageNumber,
            'per_page' => $itemsPerPage,
            'total' => 0,
            'data' => []
        ];

        if(!empty($collectionData)){
            $paginatedArray['total'] = $collectionData->count();
            $paginatedArray['data'] = $collectionData->forPage($pageNumber, $itemsPerPage)->values();
        }
        return [$collectionkey => $paginatedArray];
    }

}