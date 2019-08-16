<?php

namespace App\Http\Controllers\Api;

use App\Service;
use App\Symbol;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use SoapClient;
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
        $service = Service::currentService();
        try{
            $soapClient = new SoapClient($service->ws_address);
            $rowData = $soapClient->GEtData(['userName' => $service->ws_username,'passWord' => $service->ws_password]);
            $symbols = json_decode($rowData->getDataResult);
        }catch (SoapFault $e){
            throw $e;
        }
        Symbol::storeSym($symbols);
        $service->update(['ws_update_at' => now()]);
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
