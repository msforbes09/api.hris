<?php

namespace App\Services;

use Faker\Factory as Faker;
use App\Http\Controllers\Controller;

class UserServices extends Controller
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