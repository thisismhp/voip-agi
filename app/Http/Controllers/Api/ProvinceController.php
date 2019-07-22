<?php

namespace App\Http\Controllers\Api;

use App\Province;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ProvinceController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke()
    {
        return new Response(Province::with('cities')->get());
    }
}
