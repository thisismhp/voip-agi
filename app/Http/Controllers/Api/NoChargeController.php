<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class NoChargeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'state' => ['required',Rule::in([2,3])]
        ]);
        $state = $request->input('state');
        $customers = Customer::all();
        foreach ($customers as $customer) {
            $time_charge = $customer->time_charge;
            $date_charge = $customer->date_charge;
            $end_charge_checked = $customer->end_charge_checked;
            if(($time_charge == null || $time_charge < 1) && ($date_charge == null || $date_charge < now())){
                if($end_charge_checked == 1){
                    $customer->update(['end_charge_checked' => 2]);
                }
            }
        }
        $noChargeCustomer = Customer::where('end_charge_checked',$state)->get();
        return new Response($noChargeCustomer);
    }
}
