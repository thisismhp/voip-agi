<?php

namespace App\Http\Controllers\Api;

use App\DemoUser;
use App\Number;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SearchController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $key = $request->input('key');
        $demo = DemoUser::where('phone_number', 'LIKE', "%{$key}%")->get();
        $customer = [];
        $numbers = Number::where('phone_number', 'LIKE', "%{$key}%")->get();
        foreach ($numbers as $number){
            $customer[] = $number->customer;
        }
        return new Response(["demo" => $demo,"customer" => array_unique($customer)]);
    }
}
