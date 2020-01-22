<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunDev extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:dev';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all commands used in local development.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('migrate:fresh');
        $this->info(Artisan::output());

        Artisan::call('db:seed --class=TestSeeder');
        $this->info(Artisan::output());

        Artisan::call('passport:install --force');
        $this->info(Artisan::output());

    }
}
