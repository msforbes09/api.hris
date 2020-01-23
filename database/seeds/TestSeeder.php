<?php

use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DatabaseSeeder::class);
        $this->call(__UsersTableSeeder::class);
        $this->call(__ClientTableSeeder::class);
        $this->call(__ApplicantTableSeeder::class);
    }
}
