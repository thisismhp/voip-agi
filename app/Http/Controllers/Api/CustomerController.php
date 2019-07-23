<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use App\Http\Controllers\Controller;
use App\Number;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return new Response(Customer::with(['province','city'])->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);
        $customer = new Customer();
        $customer->name = $request->input('name');
        $customer->nation_code = $request->input('nation_code');
        $customer->birth_date = $request->input('birth_date');
        $customer->province_id = $request->input('province_id');
        $customer->city_id = $request->input('city_id');
        $customer->address = $request->input('address');
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
        $customer = Customer::findOrFail($id);
        $customer->numbers;
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
        $customer = Customer::findOrFail($id);
        $this->validateRequest($request, true, $customer->numbers);
        $customer->update($request->except(['id']));
        return new Response($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return new Response(["message" => "deleted"]);
    }

    /**
     * Validate the request.
     *
     * @param Request $request
     * @param bool $isUpdate
     */
    private function validateRequest(Request $request, $isUpdate=false, $lNumbers = null){
        $request->validate([
            'name' => ['required','string','max:100'],
            'nation_code' => ['required','string','max:20'],
            'birth_date' => ['required','string','max:20'],
            'province_id' => ['required','exists:provinces,id'],
            'city_id' => ['required','exists:cities,id'],
            'address' => ['required','string','max:255'],
            'phone_number' => ['required','string','max:20'],
            'numbers' => ['nullable','array'],
            'numbers.*.phone_number_type_id' => ['required','exists:phone_number_types,id'],
            'numbers.*.phone_number' => ['required','string','max:20','distinct'],
            'numbers.*.charge_type_id' => ['required','exists:charge_types,id'],
            'numbers.*.is_active' => ['required','boolean'],
        ]);
        if ($isUpdate){
            $rules = [];
            $lastNumbers = [];
            foreach ($lNumbers as $k=>$lNumber) {
                $lastNumbers[$k]= $lNumber->phone_number;
            }
            $numbers = $request->input('numbers');
            foreach ($numbers as $i=>$num) {
                $rules["numbers.$i.phone_number"] = [Rule::unique('numbers','phone_number')->whereNot('phone_number',$lastNumbers)];
            }
            $request->validate($rules);
        }else{
            $request->validate(['numbers.*.phone_number' => ['unique:numbers,phone_number']]);
        }
    }
}
