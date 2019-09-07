<?php

namespace App\Http\Controllers\Api;

use App\Unit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class UnitController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke()
    {
        return new Response(Unit::all());
    }
}
