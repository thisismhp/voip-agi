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
        $request->validate(['destination_type' => ['required',Rule::in([1,2])],]);
        $export = [];
        $destinationType = $request->input('destination_type');
        $this->validateRequest($request, $destinationType);
        $items = $request->input('items');
        foreach ((array)$items as $item) {
            $destination = null;
            $chargeRecord = null;
            $chargeTypeId = $item['charge_type_id'];
            $destinationId = $item['destination_id'];
            $value = (int)$item['value'];
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
                $destination->update(['time_charge' => $newCharge]);
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
                $destination->update(['date_charge' => $newCharge]);
            }
            array_push($export, $destination);
        }
        return new Response($request);
    }

    public function validateRequest(Request $request, $destinationType)
    {
        $items = $request->input('items');
        $rules = ['items' => ['required','array']];
        $messages = [
            'items.required' => 'لیست افزایش اعتبار الزامی است',
            'items.array' => 'لیست افزایش اعتبار اشتباه است',
        ];
        foreach ((array)$items as $i=>$item) {
            $rules["items.$i.charge_type_id"] = ['required',Rule::exists('charge_types','id')];
            $rules["items.$i.value"] = ['required','integer','min:1'];
            $messages += [
                "items.$i.charge_type_id.required" => "فیلد نوع اعتبار ردیف " . ($i+1) . " الزامی است",
                "items.$i.charge_type_id.exists" => "نوع اعتبار ردیف " . ($i+1) . "معتبر نیست",
                "items.$i.value.required" => "فیلد مقدار ردیف " . ($i+1) . " الزامی است",
                "items.$i.value.integer" => "نوع اعتبار ردیف " . ($i+1) . "معتبر نیست",
                "items.$i.value.min" => "نوع اعتبار ردیف " . ($i+1) . "معتبر نیست",
            ];
            if($destinationType == 1){
                $rules += ["items.$i.destination_id" => ['required',Rule::exists('customers', 'id')->whereNull('deleted_at')],];
            }elseif($destinationType == 2){
                $rules += ["items.$i.destination_id" => ['required',Rule::exists('demo_users', 'id')->whereNull('deleted_at')],];
            }
        }
        $request->validate($rules, $messages);
    }
}
