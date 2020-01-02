<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Responses\CurrencyResponse;
use App\Services\CurrencyService;
use App\Services\ParamsService;
use App\Services\OtsService;
use App\Exceptions\ExchangeRateNotFetched;

class CurrencyController extends Controller
{
    public function index(){

        try{
            $currencies = (new CurrencyService())->getCurrencies();
        }
        catch(\Exception $e){
            throw new ExchangeRateNotFetched($e->getMessage());
        }
        return (new CurrencyResponse())->currencies($currencies);
    }

    public function getExchangeRate($currencyName){
		$paramsService = new ParamsService();
		$params = $paramsService->currenciesParams($currencyName);
		$data = (new OtsService('api'))->getResponse($params['QueryId'],$params);
		return (new CurrencyResponse())->exchangeRate($data);
    }
    
}
