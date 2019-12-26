<?php

use Illuminate\Database\Seeder;

class UserTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'administrator',
            'applicant',
            'recruiter'
        ];

        foreach ($types as $type) {
            DB::table('user_types')->insert([
                'name' => $type
            ]);
        }
    }
}
