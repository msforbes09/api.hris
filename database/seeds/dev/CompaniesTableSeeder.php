<?php

use App\Company;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    public function run()
    {
        Company::insert([
            ['code' => 'SRI', 'name' => 'Service Resources Inc.'],
            ['code' => 'CLSC', 'name' => 'Central Labor Service Cooperative'],
            ['code' => 'JTCI', 'name' => 'Job Skills Training Center Inc'],
            ['code' => 'MNG', 'name' => 'MNGoodHealth Inc.'],
            ['code' => 'PSSI', 'name' => 'People Link Staffing Solutions Inc'],
            ['code' => 'SOFI', 'name' => 'Serbisyo Outsourcing Facilities Inc'],
        ]);
    }
}
