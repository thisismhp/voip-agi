<?php

namespace App\Http\Controllers\Api;

use App\Service;
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
        $request->validate([
            'is_active' => 'nullable|boolean',
            'unit_id' => 'nullable|exists:manage.units,id',
            'm_file' => 'nullable|mimetypes:audio/mpeg',
            'w_file' => 'nullable|mimetypes:audio/mpeg'
        ]);
        $symbol = Symbol::findOrFail($id);
        $path = "services/".Service::currentService()->id."/symbols/".$symbol->id;
        if($request->file('m_file') != null){
            $this->storeFiles($request, $path, ['m_file'], $symbol);
        }
        if($request->file('w_file') != null){
            $this->storeFiles($request, $path, ['w_file'], $symbol);
        }
        $symbol->update($request->only(['is_active','unit_id']));
        $symbol = Symbol::findOrFail($id);
        return new Response($symbol);
    }
}
