<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('get-token','AuthController@getToken');

Route::post('incoming',function(){
    $service = new \App\Services\ComService();
    return $service->syncIncomingData(request()->data);
})->middleware("verify_server_call");

Route::group(['middleware' => 'token_auth'], function(){
    Route::post('clients/{clientCrmId}/vat-analysis/data','VatAnalysisController@getData'); // feature test done
    Route::get('partners/{partnerId}/clients','ClientController@getClientsByUser'); // feature test done
    Route::get('currencies','CurrencyController@index'); // feature test done
    Route::post('events/list','EventController@getLatestEvents');
    // Admin role only can access this following APIs
    Route::get('partner/companies','PartnerCompanyController@getPartnerCompaniesList'); // feature test done
    Route::get('partners/list/{companyId}', 'UserController@getPartnersByCompanyId'); // feature test done
    Route::get('partner-company/{companyId}', 'PartnerCompanyController@findById'); // feature test done
});
