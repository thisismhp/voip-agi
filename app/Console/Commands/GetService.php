<?php

namespace App\Console\Commands;

use App\Service;
use Illuminate\Console\Command;

class GetService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:service {number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get service';

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
        $lineType = 'm';
        $number =  $this->argument("number");
        $service = Service::where([['m_line',$number],['is_active',1]])->get()->last();
        if ($service == null){
            $service = Service::where([['w_line',$number],['is_active',1]])->get()->last();
            if ($service == null){
                echo "NO";
                return;
            }
            $lineType = 'w';
        }
        echo "$service->id,$service->is_active,$lineType,$service->queue_id,$service->menu_opr_key,$service->no_charge_opr_key,$service->no_charge_sms_key";
        return;
    }
}
