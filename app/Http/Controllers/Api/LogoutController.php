<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke()
    {
        $token = auth()->user()->token();
        $token->delete();
        return new Response("OK");
    }
}
