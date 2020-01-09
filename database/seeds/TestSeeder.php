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
        $this->call(__UserTypesTableSeeder::class);
        $this->call(__ModuleTableSeeder::class);
        $this->call(__UsersTableSeeder::class);
        $this->call(__AccessTableSeeder::class);
        $this->call(__CompaniesTableSeeder::class);
    }
}
