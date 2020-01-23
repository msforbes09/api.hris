<?php

use Illuminate\Database\Seeder;

class UserTypesTableSeeder extends Seeder
{
    protected $types = [
        'System Admin',
        'Administrator',
        'Manager',
        'Evaluator',
        'Encoder',
    ];

    public function run()
    {
        foreach ($this->types as $type) {
            DB::table('user_types')->insert([
                'name' => $type
            ]);
        }
    }
}
