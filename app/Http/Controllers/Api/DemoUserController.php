<?php

namespace App\Http\Controllers\Api;

use App\DemoUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class DemoUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke()
    {
        return new Response(DemoUser::paginate(20));
    }
}
