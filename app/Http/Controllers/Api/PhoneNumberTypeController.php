<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\PhoneNumberType;
use Illuminate\Http\Response;

class PhoneNumberTypeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return new Response(PhoneNumberType::all());
    }
}
