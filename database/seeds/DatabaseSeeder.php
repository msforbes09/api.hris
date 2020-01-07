<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Production Seeder

        // Test Seeder
        $this->call(TestSeeder::class);
    }
}
