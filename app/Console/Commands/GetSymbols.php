<?php

namespace App\Console\Commands;

use App\DefaultSymbols;
use App\Service;
use App\ServiceGroup;
use Illuminate\Console\Command;

class GetSymbols extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:symbols {serviceID} {g}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get symbols';

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
        $g = $this->argument('g');
        $serviceID = $this->argument("serviceID");
        $service = Service::find($serviceID);
        if($service == null){
            return;
        }
        config(['database.connections.service.database'=> "service-$serviceID"]);
        if($g == 0){
            $symbols = DefaultSymbols::orderBy('priority', 'ASC')->get('symbol_id');
            $exp = '';
            foreach ($symbols as $symbol) {
                $unit = ($symbol->symbol->unit != null)?":".$symbol->symbol->unit->id:null;
                $exp .= $symbol->symbol->id.":".$symbol->symbol->buyPriceFormatted.":".$symbol->symbol->sellPriceFormatted.$unit.",";
            }
            echo $exp;
            return;
        }
        $serviceGroup = ServiceGroup::find($g);
        $symbols = $serviceGroup->symbols()->orderBy('service_group_symbol.priority')->get();
        $exp = '';
        foreach ($symbols as $symbol) {
            $unit = ($symbol->unit != null)?":".$symbol->unit->id:null;
            $exp .= $symbol->id.":".$symbol->buyPriceFormatted.":".$symbol->sellPriceFormatted.$unit.",";
        }
        echo $exp;
        return;
    }
}
