<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ClientBranch;
use Faker\Generator as Faker;

$factory->define(ClientBranch::class, function (Faker $faker) {
    return [
        'code' => $faker->buildingNumber . $faker->buildingNumber,
        'name' => $faker->streetName,
        'client_id' => factory(App\Client::class),
    ];
});
