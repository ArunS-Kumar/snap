<?php
namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository extends BaseRepository{
	
	public function getAllCurrencies(){
		return Currency::all();
	}
}