<?php

use Illuminate\Database\Seeder;

class KeywordsTableSeeder extends Seeder
{
    protected $keywords = [
        'gender' => ['Male', 'Female'],
        'civil_status' => ['Single', 'Married', 'Separated', 'Widowed'],
        'tax_code' => ['S', 'M', 'S1', 'M1', 'S2', 'M2', 'S3', 'M3' ],
        'relationship' => ['Father', 'Mother', 'Brother', 'Sister', 'Wife', 'Husband', 'Children'],
        'education_level' => ['Elementary', 'High School', 'Vocational', 'College'],
        'height' => ['in', 'cm'],
        'weight' => ['lbs', 'kg'],
        'citizenship' => ['Filipino'],
        'religion' => ['Roman Catholic']
    ];

    public function run()
    {
        foreach($this->keywords as $key => $values)
        {
            $id = DB::table('keys')->insertGetId([
                'name' => $key
            ]);

            foreach($values as $value)
            {
                DB::table('keywords')->insert([
                    'key_id' => $id,
                    'value' => $value
                ]);
            }
        }
    }
}
