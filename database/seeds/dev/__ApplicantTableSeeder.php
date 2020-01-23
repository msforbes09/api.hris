<?php

use Illuminate\Database\Seeder;

class __ApplicantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Applicant::class, 100)->create();
    }
}
