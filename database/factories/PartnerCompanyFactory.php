<?php

use Faker\Generator as Faker;

$factory->define(App\Models\PartnerCompany::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'unique_id' => '',
        'logo' => $faker->imageUrl($width = 640, $height = 480),
        'is_active' => true
    ];
});
