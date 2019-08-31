<?php

namespace App\Console\Commands;

use App\DemoUser;
use App\Number;
use App\Service;
use Illuminate\Console\Command;

class CheckCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:customer {serviceID} {number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check customer';

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
        $num = $this->argument("number");
        $serviceID = $this->argument("serviceID");
        $service = Service::find($serviceID);
        if($service == null){
            return;
        }
        config(['database.connections.service.database'=> "service-$serviceID"]);
        $number = Number::where('phone_number',$num)->get()->last();
        if ($number == null){
            if($service->demo_is_free == 1){
                echo "OK";
                return;
            }
            $demoUser = DemoUser::where('phone_number',$num)->get()->last();
            if($demoUser == null){
                $demoUser = new DemoUser();
                $demoUser->phone_number = $num;
                if($service->demo_charge_type_id == 1){
                    $demoUser->time_charge = $service->demo_first_charge;
                }else{
                    $lastChargeDate = now();
                    $demoUser->date_charge = date("Y/m/d 23:59:59",strtotime("$lastChargeDate + $service->demo_first_charge day"));
                }
                $demoUser->save();
            }
            if($service->demo_charge_type_id == 1){
                if ($demoUser->time_charge > 0){
                    $demoUser->update(['time_charge' => $demoUser->time_charge - 1]);
                    echo "OK";
                    return;
                }else{
                    echo "NO";
                    return;
                }
            }else{
                if ($demoUser->date_charge > now()){
                    echo "OK";
                    return;
                }else{
                    echo "NO";
                    return;
                }
            }
        }
        else {
            if($service->customer_is_free == 1){
                echo "OK";
                return;
            }
            $customer = $number->customer;
            if ($customer == null or $customer->is_active != 1 or $number->is_active != 1) {
                echo 'INACTIVE';
            }else{
                if($number->charge_type_id == 1){
                    if ($customer->time_charge > 0){
                        $customer->update(['time_charge' => $customer->time_charge - 1]);
                        echo "OK";
                        return;
                    }else{
                        echo "NO";
                        return;
                    }
                }else{
                    if ($customer->date_charge > now()){
                        echo "OK";
                        return;
                    }else{
                        echo "NO";
                        return;
                    }
                }
            }
        }
    }
}
