<?php

use App\UserType;
use App\ModuleAction;
use Illuminate\Database\Seeder;

class AccessTableSeeder extends Seeder
{
    public function run()
    {
        $admin = UserType::where('id', 1)->first();

        $admin->moduleActions()->sync(ModuleAction::get());
    }
}
