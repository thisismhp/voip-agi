<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class CheckChargeController extends Controller
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
           'customer_id' => ['required',Rule::exists('service.customers','id')->whereNull('deleted_at')]
        ]);
        $customer = Customer::find($request->input('customer_id'));
        if($customer->end_charge_checked == 2){
            $customer->update(['end_charge_checked' => 3]);
        }
        return new Response($customer);
    }
}
