<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Default ADMIN ACCOUNT
        DB::table('users')->insert([
            'user_type_id' => 1,
            'name' => 'SRI Admin',
            'username' => 'sriadmin',
            'email' => 'sriwebapp@gmail.com',
            'active' => 1,
            'password' => bcrypt(config('app.default_pass')),
            'remember_token' => Str::random(10),
        ]);
    }
}
