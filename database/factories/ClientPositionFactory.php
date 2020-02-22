<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ClientPosition;
use Faker\Generator as Faker;

$factory->define(ClientPosition::class, function (Faker $faker) {
    return [
        'code' => $faker->buildingNumber . $faker->buildingNumber,
        'name' => $faker->jobTitle,
        'client_id' => factory(App\Client::class),
    ];
});
