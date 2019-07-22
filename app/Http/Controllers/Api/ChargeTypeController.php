<?php

namespace App\Http\Controllers\Api;

use App\ChargeType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ChargeTypeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return new Response(ChargeType::all());
    }
}
