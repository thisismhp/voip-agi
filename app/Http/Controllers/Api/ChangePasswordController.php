<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ChangePasswordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'password' => ['required','string','min:8','confirmed'],
        ]);
        $this->user()->update(['password' => bcrypt($request->input('password'))]);
        return new Response();
    }
}
