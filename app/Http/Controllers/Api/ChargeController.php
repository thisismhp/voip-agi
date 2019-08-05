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
        $export = [];
        $destinationType = $request->input('destination_type');
        $chargeTypeId = $request->input('charge_type_id');
        $value = (int)$request->input('value');
        $this->validateRequest($request, $destinationType);
        $items = $request->input('items');
        foreach ((array)$items as $item) {
            $destination = null;
            $chargeRecord = null;
            $destinationId = $item;
            if($destinationType == 1) {
                $destination = Customer::find($destinationId);
                $chargeRecord = new CustomerCharge();
            }elseif ($destinationType == 2){
                $destination = DemoUser::find($destinationId);
                $chargeRecord = new DemoUserCharge();
            }
            $chargeRecord->destination_id = $destinationId;
            $chargeRecord->value = $value;
            $chargeRecord->charge_type_id = $chargeTypeId;
            $chargeRecord->save();
            if($chargeTypeId == 1) {
                $lastCharge = (int)$destination->time_charge;
                $newCharge = $lastCharge + $value;
                $destination->update(['time_charge' => $newCharge,'end_charge_checked' => 1]);
            }
            elseif ($chargeTypeId == 2){
                $lastCharge = $destination->date_charge;
                $lastChargeDate = now();
                if($lastCharge != null){
                    if(now() < $lastCharge){
                        $lastChargeDate = $lastCharge;
                    }
                }
                $newCharge = date("Y/m/d H:i:s",strtotime("$lastChargeDate + $value day"));
                $destination->update(['date_charge' => $newCharge,'end_charge_checked' => 1]);
            }
            array_push($export, $destination);
        }
        return new Response($request);
    }

    public function validateRequest(Request $request, $destinationType)
    {
        $request->validate([
            'destination_type' => ['required',Rule::in([1,2])],
            'charge_type_id' => ['required',Rule::exists('charge_types','id')],
            'value' => ['required','integer','min:1'],
            'items' => ['required','array','min:1']
            ]);
        $items = $request->input('items');
        $rules = [];
        $messages = [
            'items.required' => 'لیست افزایش اعتبار الزامی است',
            'items.array' => 'لیست افزایش اعتبار اشتباه است',
        ];
        foreach ((array)$items as $i=>$item) {
            if($destinationType == 1){
                $rules += ["items.$i" => [Rule::exists('customers', 'id')->whereNull('deleted_at')],];
                $messages += [
                    "items.$i.exists" => "فیلد مشتری ردیف " . ($i+1) . "معتبر نیست",
                ];
            }elseif($destinationType == 2){
                $rules += ["items.$i" => [Rule::exists('demo_users', 'id')->whereNull('deleted_at')],];
                $messages += [
                    "items.$i.exists" => "فیلد کاربر دمو ردیف " . ($i+1) . "معتبر نیست",
                ];
            }
        }
        $request->validate($rules, $messages);
    }
}
