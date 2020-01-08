<?php

use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;

if (!function_exists('getRandomPassword'))
{
    function getRandomPassword()
    {
        $faker = Faker::create();

        return $faker->password;
    }
}

if(!function_exists('appLog'))
{
    function appLog($action, $user, $model = NULL)
    {
        if($model != NULL) {
            Log::info('ACTION: ' . $action . ' | ' . ' by USER:' . $user . ' | on MODEL: ' . get_class($model) . ' with ID:' . $model->id);
        } else {
            Log::info('ACTION: ' . $action . ' | ' . ' by USER:' . $user);
        }
    }
}
