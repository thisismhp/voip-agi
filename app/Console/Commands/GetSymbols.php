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
            echo "NO";
            return;
        }
        config(['database.connections.service.database'=> "service-$serviceID"]);
        if($g == 0){
            $symbols = DefaultSymbols::orderBy('priority', 'ASC')->get('symbol_id');
            $exp = '';
            foreach ($symbols as $symbol) {
                if($symbol->symbol->is_active){
                    $unit = ($symbol->symbol->unit != null)?":".$symbol->symbol->unit->id:null;
                    $exp .= $symbol->symbol->id.":".$symbol->symbol->buyPriceFormatted.":".$symbol->symbol->sellPriceFormatted.$unit.",";
                }
            }
            echo $exp;
            return;
        }
        $serviceGroup = ServiceGroup::where([['id',$g],['is_active',1]])->get()->last();
        if($serviceGroup == null){
            echo "NO";
            return;
        }
        $symbols = $serviceGroup->symbols()->where('symbols.is_active',1)->orderBy('service_group_symbol.priority')->get();
        $exp = '';
        foreach ($symbols as $symbol) {
            $unit = ($symbol->unit != null)?":".$symbol->unit->id:null;
            $exp .= $symbol->id.":".$symbol->buyPriceFormatted.":".$symbol->sellPriceFormatted.$unit.",";
        }
        echo $exp;
        return;
    }
}
