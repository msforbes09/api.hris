<?php

use App\User;
use Illuminate\Database\Seeder;

class __UsersTableSeeder extends Seeder
{
    protected $users = [
        [
            'name' => 'Isaac Aranas',
            'username' => 'isaac',
            'email' => 'isaac.aranas@csic.ph',
        ],
        [
            'name' => 'Arnel Forbes',
            'username' => 'arnel',
            'email' => 'arnel.forbes@csic.ph',
        ],
        [
            'name' => 'Kenneth Paul Nava',
            'username' => 'kenneth',
            'email' => 'kenneth.nava@csic.ph',
        ]
    ];

    public function run()
    {
        foreach ($this->users as $user) {
            DB::table('users')->insert([
                'user_type_id' => 1,
                'name' => $user['name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'active' => 1,
                'password' => bcrypt(config('app.default_pass')),
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
