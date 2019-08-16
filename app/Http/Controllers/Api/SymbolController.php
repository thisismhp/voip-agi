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
        foreach ((array)$symbols as $symbol) {
             $item = ((array)$symbol);
            if($item['symbolId'] != 0 and Symbol::where('symbolId',$item['symbolId'])->count() == 0){
                $newSymbol = new Symbol();
                $newSymbol->symbolId = $item['symbolId'];
                $newSymbol->fName = $item['fName'];
                $newSymbol->eName = $item['eName'];
                $newSymbol->buyPriceFormatted = $item['buyPriceFormatted'];
                $newSymbol->sellPriceFormatted = $item['sellPriceFormatted'];
                $newSymbol->transPriceFormatted = $item['transPriceFormatted'];
                $newSymbol->buysellDiff = $item['buysellDiff'];
                $newSymbol->vol = $item['vol'];
                $newSymbol->volTick = $item['volTick'];
                $newSymbol->direction = $item['direction'];
                $newSymbol->transType = $item['transType'];
                $newSymbol->dsabt = $item['dsabt'];
                $newSymbol->save($item);
            }
        }
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
