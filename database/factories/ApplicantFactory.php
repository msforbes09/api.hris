<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Applicant;
use Faker\Generator as Faker;

$factory->define(Applicant::class, function (Faker $faker) {
    return [
        'last_name' => $faker->firstName,
        'first_name' => $faker->lastName,
        'middle_name' => $faker->lastName,
        'nick_name' => $faker->firstName,
        'current_address' => $faker->address,
        'permanent_address' => $faker->address,
        'birth_date' => $faker->date,
        'birth_place' => $faker->state,
        'gender' => ['male', 'female'][rand(0, 1)],
        'height' => rand(60, 180) . ' cm',
        'weight' => rand(40, 80) . ' kg',
        'email' => $faker->email,
        'contact_no' => '639568030931'
    ];
});
