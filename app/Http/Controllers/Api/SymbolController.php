<?php

namespace App\Http\Controllers\Api;

use App\Symbol;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use SoapFault;

class SymbolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return new Response(Symbol::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     * @throws SoapFault
     */
    public function store()
    {
        Symbol::storeSym();
        return new Response(Symbol::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $symbol = Symbol::findOrFail($id);
        $symbol->update($request->only(['is_active','unit_id']));
        return new Response($symbol);
    }
}
