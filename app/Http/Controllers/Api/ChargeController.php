<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use App\CustomerCharge;
use App\DemoUser;
use App\DemoUserCharge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ChargeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $destinationType = $request->input('destination_type');
        $date_value = (int)$request->input('date_value');
        $time_value = (int)$request->input('time_value');
        $destinationID = $request->input('destination');
        $this->validateRequest($request, $destinationType);
        $items = [];
        $chargeRecord = null;
        if ($destinationType == 1) {
            $items[] = Customer::find($destinationID);
        }
        elseif ($destinationType == 2){
            $items[] = DemoUser::find($destinationID);
        }
        elseif ($destinationType == 3) {
            $items = Customer::all();
        }
        elseif ($destinationType == 4){
            $items = DemoUser::all();
        }
        foreach ($items as $item) {
            if($time_value > 0) {
                $lastCharge = (int)$item->time_charge;
                $newCharge = $lastCharge + $time_value;
                $item->update(['time_charge' => $newCharge,'end_charge_checked' => 1,'end_charge_comment' => null]);
                if($destinationType == 1 or $destinationType == 3){
                    $chargeRecord = new CustomerCharge();
                }else{
                    $chargeRecord = new DemoUserCharge();
                }
                $chargeRecord->destination_id = $item->id;
                $chargeRecord->value = $time_value;
                $chargeRecord->charge_type_id = 1;
                $chargeRecord->save();
            }
            if ($date_value > 0){
                if($destinationType == 1 or $destinationType == 3){
                    $chargeRecord = new CustomerCharge();
                }else{
                    $chargeRecord = new DemoUserCharge();
                }
                $lastCharge = $item->date_charge;
                $lastChargeDate = now();
                if($lastCharge != null){
                    if(now() < $lastCharge){
                        $lastChargeDate = $lastCharge;
                    }
                }
                $newCharge = date("Y/m/d 23:59:59",strtotime("$lastChargeDate + $date_value day"));
                $item->update(['date_charge' => $newCharge,'end_charge_checked' => 1,'end_charge_comment' => null]);
                $chargeRecord->destination_id = $item->id;
                $chargeRecord->value = $date_value;
                $chargeRecord->charge_type_id = 1;
                $chargeRecord->save();
            }
        }
        return new Response(["message" => "OK"]);
    }

    public function validateRequest(Request $request, $destinationType)
    {
        $request->validate([
            'destination_type' => ['required',Rule::in([1,2,3,4])],
            'date_value' => ['nullable','integer'],
            'time_value' => ['nullable','integer'],
            ]);
        $rules = [];
            if($destinationType == 1){
                $rules += ["destination" => ['required',Rule::exists('service.customers', 'id')->whereNull('deleted_at')],];
            }elseif($destinationType == 2){
                $rules += ["destination" => ['required',Rule::exists('service.demo_users', 'id')->whereNull('deleted_at')],];
            }
        $request->validate($rules);
    }
}
