<?php

use App\User;
use Illuminate\Database\Seeder;

class __UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Default ADMIN ACCOUNT
        DB::table('users')->insert([
            'user_type_id' => 1,
            'name' => 'SRI Admin',
            'username' => 'sriadmin',
            'email' => 'sriwebapp@gmail.com',
            'active' => 1,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        DB::table('users')->insert([
            'user_type_id' => 1,
            'name' => 'Isaac Aranas',
            'username' => 'isaac',
            'email' => 'isaac.aranas@csic.ph',
            'active' => 1,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        DB::table('users')->insert([
            'user_type_id' => 1,
            'name' => 'Arnel Forbes',
            'username' => 'arnel',
            'email' => 'arnel.forbes@csic.ph',
            'active' => 1,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        DB::table('users')->insert([
            'user_type_id' => 1,
            'name' => 'Kenneth Paul Nava',
            'username' => 'kenneth',
            'email' => 'kenneth.nava@csic.ph',
            'active' => 1,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        // factory(User::class, 10)->create();
    }
}
