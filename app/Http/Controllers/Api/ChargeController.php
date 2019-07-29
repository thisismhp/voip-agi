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
        $this->validateRequest($request);
        return new Response($request);
    }

    public function validateRequest(Request $request)
    {
        $request->validate([
            'charge_type_id' => ['required',Rule::exists('charge_types','id')],
            'destination_type' => ['required',Rule::in([1,2])],
            'destination_id' => ['required'],
            'value' => ['required','integer','min:1'],

        ]);
        $destinationType = $request->input('destination_type');
        if($destinationType == 1){
            $request->validate([
                'destination_id' => [Rule::exists('customers', 'id')->whereNull('deleted_at')]
            ]);
        }else if($destinationType == 2){
            $request->validate([
                'destination_id' => [Rule::exists('demo_users', 'id')->whereNull('deleted_at')]
            ]);
        }
    }
}
