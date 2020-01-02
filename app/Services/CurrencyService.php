<?php
namespace App\Services;

use App\Repositories\CurrencyRepository;
use App\Services\ParamsService;
use App\Services\OtsService;

class CurrencyService extends BaseService
{

	public function getCurrencies()
	{
		$currencies = (new CurrencyRepository())->getAllCurrencies()->toArray();
    	
    	$currenciesNames = [];
    	foreach ($currencies as $key => $value)
    		$currenciesNames[] = $value['name'];

    	$paramsService = new ParamsService();
    	$params = $paramsService->currenciesParams($currenciesNames);
    	$data = (new OtsService('api'))->getResponse($params['QueryId'],$params);

    	foreach ($currencies as $key => $value) {
    		
    		foreach ($data as $k => $dvalue) {
    			if($value['name'] == $dvalue['Properties'][0]['Value']) {
    				$currencies[$key]['exchange_rate'] = $dvalue['Properties'][1]['Value']; break;
    			}
    		}
    		if(!isset($currencies[$key]['exchange_rate'])) {
    			$currencies[$key]['exchange_rate'] = 1;
    		}
    	}

    	return $currencies;
	}
	
}
