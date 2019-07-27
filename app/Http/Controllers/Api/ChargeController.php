<?php

namespace App\Http\Controllers\Api;

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
        $request->validate([
            'charge_type_id' => ['required',Rule::exists('charge_types','id')],
            'destination_type' => ['required',Rule::in([1,2])],
            'destination_id' => ['required'],
            'value' => ['required'],

        ]);
        $chargeType = $request->input('charge_type_id');
        $destinationType = $request->input('destination_type');
        if($chargeType == 1){
            $request->validate([
                'value' => ['integer','min:1'],
            ]);
        }else if($chargeType == 2){
            $request->validate([

            ]);
        }
        if($destinationType == 1){
            $request->validate([
                'destination_id' => [Rule::exists('customers', 'id')->whereNull('deleted_at')]
            ]);
        }else if($destinationType == 2){
            $request->validate([
                'destination_id' => [Rule::exists('demo_users', 'id')->whereNull('deleted_at')]
            ]);
        }
        return new Response($request);
    }
}
