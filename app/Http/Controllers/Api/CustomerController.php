<?php

namespace App\Http\Controllers\Api;

use App\Customers;
use App\Http\Controllers\Controller;
use App\Number;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return new Response(Customers::with(['province','city'])->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $customer = new Customers();
        $customer->name = $request->input('name');
        $customer->nation_code = $request->input('nation_code');
        $customer->birth_date = $request->input('birth_date');
        $customer->province_id = $request->input('province_id');
        $customer->city_id = $request->input('city_id');
        $customer->address = $request->input('province_id');
        $customer->phone_number = $request->input('phone_number');
        $customer->save();
        $numbers = $request->input('numbers');
        foreach ($numbers as $num) {
            $number = new Number();
            $number->customer_id = $customer->id;
            $number->phone_number_type_id = $num['phone_number_type_id'];
            $number->phone_number = $num['phone_number'];
            $number->charge_type_id = $num['charge_type_id'];
            $number->is_active = $num['is_active'];
            $number->save();
        }
        return new Response($customer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $customer = Customers::findOrFail($id);
        return new Response($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        return new Response([$request,$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $customer = Customers::findOrFail($id);
        $customer->delete();
        return new Response(["message" => "deleted"]);
    }
}
