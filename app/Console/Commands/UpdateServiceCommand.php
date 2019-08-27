<?php

namespace App\Console\Commands;

use App\Service;
use App\Symbol;
use Illuminate\Console\Command;
use SoapFault;

class UpdateServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @throws SoapFault
     */
    public function handle()
    {
        $services = Service::all();
        foreach ($services as $service) {
            if(strtotime(now()) - strtotime($service->ws_update_at) > (int)$service->ws_update_interval){
                Symbol::storeSym($service);
                $this->info("Service $service->id Updated!");
            }
        }
        return true;
    }
}
