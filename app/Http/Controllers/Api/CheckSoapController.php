<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use SoapClient;
use SoapFault;

class CheckSoapController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     * @throws SoapFault
     */
    public function __invoke(Request $request)
    {
        $soapClient = new SoapClient($request->input('ws_address'));
        $rowData = $soapClient->GEtData(['userName' => $request->input('ws_username'),'passWord' => $request->input('ws_password')]);
        return new Response((count(json_decode($rowData->getDataResult)) < 2)?"ERROR":"OK");
    }
}
