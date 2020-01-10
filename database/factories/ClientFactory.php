<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Client;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    return [
        'code' => $faker->buildingNumber,
        'name' => $faker->state,
        'company_id' => rand(1, 3)
    ];
});
