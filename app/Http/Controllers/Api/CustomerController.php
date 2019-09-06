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
        $customer->is_active = $request->input('is_active');
        $customer->save();
        $numbers = $request->input('numbers');
        foreach ($numbers as $num) {
            $this->saveNumber($customer, $num);
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
        $cusNumbers = $customer->numbers;
        $reqNumbers = $request->input('numbers');
        foreach ($cusNumbers as $cusNumber) {
            $isDeleted = true;
            foreach ($reqNumbers as $reqNumber) {
                if(array_key_exists('id',$reqNumber)){
                    $numId = $reqNumber['id'];
                    if($numId == $cusNumber->id){
                        unset($reqNumber['id']);
                        $cusNumber->update($reqNumber);
                        $isDeleted = false;
                        break;
                    }
                }
            }
            if($isDeleted){
                $cusNumber->delete();
            }
        }
        foreach ($reqNumbers as $num) {
            if(!array_key_exists('id',$num) and !Number::all()->contains('phone_number',$num['phone_number'])){
                $this->saveNumber($customer, $num);
            }
        }
        $customer = Customer::findOrFail($id);
        return new Response($customer);
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

    private function saveNumber($customer, $num)
    {
        $number = new Number();
        $number->customer_id = $customer->id;
        $number->phone_number_type_id = $num['phone_number_type_id'];
        $number->phone_number = $num['phone_number'];
        $number->charge_type_id = $num['charge_type_id'];
        $number->is_active = $num['is_active'];
        $number->save();
    }

    /**
     * Validate the request.
     *
     * @param Request $request
     * @param bool $isUpdate
     * @param null $lNumbers
     */
    private function validateRequest(Request $request, $isUpdate=false, $lNumbers = null){
        $lastNumbers = [];
        if($isUpdate){
            foreach ($lNumbers as $k=>$lNumber) {
                $lastNumbers[$k]= $lNumber->phone_number;
            }
        }
        $numbers = $request->input('numbers');
        $rules = [
            'name' => ['required','string','max:100'],
            'nation_code' => ['required','string','max:20'],
            'birth_date' => ['required','string','max:20'],
            'province_id' => ['required','exists:manage.provinces,id'],
            'city_id' => ['required','exists:manage.cities,id'],
            'address' => ['required','string','max:255'],
            'phone_number' => ['required','string','max:20'],
            'numbers' => ['nullable','array'],
            'is_active' => ['required','boolean'],
        ];
        $messages = [];
        $distinctMessages = [];
        foreach ((array)$numbers as $i=>$number) {
            $rules += [
                "numbers.$i.phone_number_type_id" => ['required','exists:manage.phone_number_types,id'],
                "numbers.$i.phone_number" => ['required','string','max:20'],
                "numbers.$i.charge_type_id" => ['required','exists:manage.charge_types,id'],
                "numbers.$i.is_active" => ['required','boolean'],
            ];
            if ($isUpdate){
                $rules["numbers.$i.phone_number"] = ['required','string','max:20',Rule::unique('service.numbers','phone_number')->whereNot('phone_number',$lastNumbers)->whereNull('deleted_at'),];
            }else{
                $rules["numbers.$i.phone_number"] = ['required','string','max:20',Rule::unique('service.numbers','phone_number')->whereNull('deleted_at')];
            }
            $messages += [
                "numbers.$i.phone_number_type_id.required" => "فیلد عنوان شماره تلفن ردیف " . ($i+1) . " الزامی است",
                "numbers.$i.phone_number_type_id.exists" => "عنوان شماره تلفن ردیف " . ($i+1) . " معتبر نیست",
                "numbers.$i.charge_type_id.required" => "فیلد نوع اعتبار شماره تلفن ردیف " . ($i+1) . " الزامی است",
                "numbers.$i.charge_type_id.exists" => "نوع اعتبار شماره تلفن ردیف " . ($i+1) . " معتبر نیست",
                "numbers.$i.is_active.required" => "فیلد وضعیت فعال شماره تلفن ردیف " . ($i+1) . " الزامی است",
                "numbers.$i.is_active.boolean" => "وضعیت فعال شماره تلفن ردیف " . ($i+1) . " معتبر نیست",
                "numbers.$i.phone_number.required" => "فیلد شماره تلفن شماره تلفن ردیف " . ($i+1) . " الزامی است",
                "numbers.$i.phone_number.string" => "فیلد شماره تلفن شماره تلفن ردیف " . ($i+1) . " باید به صورت رشته باشد",
                "numbers.$i.phone_number.max" => "فیلد شماره تلفن شماره تلفن ردیف " . ($i+1) . " نباید بیشتر از " . ":max" . " کاراکتر باشد",
                "numbers.$i.phone_number.unique" => "فیلد  شماره تلفن  ردیف " . ($i+1) . " قبلا ثبت شده است",
            ];
            $distinctMessages += [
                "numbers.$i.phone_number.distinct" => "فیلد شماره تلفن شماره تلفن ردیف " . ($i+1) . " در ردیف دیگر این فرم وجود دارد",
            ];
        }
        $request->validate($rules,$messages);
        $request->validate(['numbers.*.phone_number' => ['distinct'],],$distinctMessages);
    }
}
