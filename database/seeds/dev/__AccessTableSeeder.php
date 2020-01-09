<?php

use Illuminate\Database\Seeder;

class __AccessTableSeeder extends Seeder
{
    public function run()
    {
        $initialActions = [1,2,3,4,5];

        foreach ($initialActions as $id) {
            DB::table('accesses')->insert([
                'user_type_id' => 1,
                'module_action_id' => $id
            ]);
        }
    }
}
