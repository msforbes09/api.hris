<?php

namespace App\Contracts;

use Faker\Factory as Faker;

trait Common
{
    public function getRandomPassword()
    {
        $faker = Faker::create();

        return $faker->password;
    }

    public function sendWelcome($user, $password)
    {
        $user->sendWelcomeNotification($password);
    }
}