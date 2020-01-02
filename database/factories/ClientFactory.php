<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Client::class, function (Faker $faker) {
    return [
       'name' => $faker->name,
       'crm_id' => $faker->uuid,
       'is_prospect' => false,
       'status' => 'active',
       'logo_url' => $faker->url
    ];
});
