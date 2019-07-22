<?php

namespace App\Http\Controllers\Api;

use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CityController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $province_id = $request->input('province_id');
        if($province_id != null){
            return new Response(City::where('province_id',$province_id)->get());
        }else{
            return new Response(City::all());
        }
    }
}
