<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class CMTController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'id' => ['required',Rule::exists('customers','id')->whereNull('deleted_at')]
        ]);
        $customer = Customer::find($request->input('id'));
        $customer->update(['end_charge_comment' => $request->input('text')]);
        return new Response($customer);
    }
}
