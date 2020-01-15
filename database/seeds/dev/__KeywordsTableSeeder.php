<?php

use Illuminate\Database\Seeder;

class __KeywordsTableSeeder extends Seeder
{
    protected $keywords = [
        'gender' => ['male', 'female'],
        'civil_status' => ['single', 'married', 'widowed'],
        'tax_code' => [],
        'relationship' => [],
        'education_level' => []
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
