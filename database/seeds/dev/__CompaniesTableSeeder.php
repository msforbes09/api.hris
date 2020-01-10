<?php

use Illuminate\Database\Seeder;

class __CompaniesTableSeeder extends Seeder
{
    protected $companies = [
        ['code' => 'C01', 'name' => 'Company 1'],
        ['code' => 'C02', 'name' => 'Company 2'],
        ['code' => 'C03', 'name' => 'Company 3']
    ];

    public function run()
    {
        DB::table('companies')->insert($this->companies);
    }
}
