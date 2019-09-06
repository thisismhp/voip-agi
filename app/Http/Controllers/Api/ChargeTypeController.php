<?php

namespace App\Http\Controllers\Api;

use App\ChargeType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChargeTypeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $chargeTypes = ChargeType::all();
        if ($request->input('is_charge') == '1'){
            unset($chargeTypes[2]);
        }
        return new Response($chargeTypes);
    }
}
