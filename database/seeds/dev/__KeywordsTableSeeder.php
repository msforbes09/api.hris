<?php

use Illuminate\Database\Seeder;

class __KeywordsTableSeeder extends Seeder
{
    protected $keywords = ['keyword 1', 'keyword 2', 'keyword 3'];

    public function run()
    {
        foreach($this->keywords as $key)
        {
            $id = DB::table('keys')->insertGetId([
                'name' => $key
            ]);

            for($i=1;$i<=5;$i++)
            {
                DB::table('keywords')->insert([
                    'key_id' => $id,
                    'value' => $key . ' value ' . $i
                ]);
            }
        }
    }
}
