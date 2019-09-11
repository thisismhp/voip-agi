<?php

namespace App\Console\Commands;

use App\Service;
use Illuminate\Console\Command;

class SendSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms {serviceID} {number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send sms to number';

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
        $serviceID = $this->argument("serviceID");
        $number = $this->argument("number");
        $service = Service::find($serviceID);
        if($service == null){
            echo "NO";
            return 0;
        }
        return $number;
    }
}
