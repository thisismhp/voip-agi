<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InitServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init {db}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init service';

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
        ini_set('max_execution_time', 300);
        $db = $this->argument("db");
        config(['database.connections.service.database'=> $db]);
        Artisan::call('migrate', ['--database' => 'service','--path' => '/database/migrations/service']);
        $this->comment("Migrated");
        return true;
    }
}
