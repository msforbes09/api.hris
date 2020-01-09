<?php

use Illuminate\Database\Seeder;

class __ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Client::class, 3)->create()
            ->each(function($client)
                {
                    $client->branches()->createMany(
                        factory(App\ClientBranch::class, 10)->make()->toArray()
                    );

                    $client->positions()->createMany(
                         factory(App\ClientPosition::class, 10)->make()->toArray()
                    );
                });
    }
}
