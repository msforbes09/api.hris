<?php

use Illuminate\Database\Seeder;

class __UserTypesTableSeeder extends Seeder
{
    protected $types = [
        'sys_admin',
        'admin',
        'manager',
        'evaluator',
        'encoder',
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
