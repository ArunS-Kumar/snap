<?php

namespace App\Responses;

use App\Exceptions\ExchangeRateNotFetched;

class CurrencyResponse extends BaseResponse
{
	public function currencies($currencies){
		return $this->successWithData("data_fetched",compact('currencies'));
	}

	public function exchangeRate($data){
		try{
			$data=$data[0]['Properties'][0]['Value'];
			return $this->successWithData("data_fetched",compact('data'));
		}
		catch(\Exception $e){
			throw new ExchangeRateNotFetched($e->getMessage());	
		}
		
		throw new ExchangeRateNotFetched("Currency exchange rate not fetched!");
	}
}