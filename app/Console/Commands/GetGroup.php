<?php

namespace App\Console\Commands;

use App\Service;
use App\ServiceGroup;
use Illuminate\Console\Command;

class GetGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:groups {serviceID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get groups';

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
        $service = Service::find($serviceID);
        if($service == null){
            echo "NO";
            return;
        }
        config(['database.connections.service.database'=> "service-$serviceID"]);
        $sgs = ServiceGroup::where('is_active', 1)->orderBy('priority','ASC')->get();
        $exp = '';
        foreach ($sgs as $sg) {
            $exp .= "$sg->id:$sg->priority,";
        }
        echo $exp;
        return;
    }
}
