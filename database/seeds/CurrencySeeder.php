<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies=[
            ['name'=>'INR','symbol'=>'₹'],
            ['name'=>'USD','symbol'=>'$'],
            ['name'=>'EUR','symbol'=>'€'],
            ['name'=>'GBP','symbol'=>'£'],
            ['name'=>'AUD','symbol'=>'$'],
            ['name'=>'CAD','symbol'=>'$'],
            ['name'=>'SGD','symbol'=>'$']
        ];
        foreach($currencies as $currency){
            DB::table('currencies')->insert([
                'name'=>$currency['name'],
                'symbol'=>$currency['symbol'],
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]);
        }
    }
}
