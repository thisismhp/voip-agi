<?php

namespace App\Http\Controllers\Api;

use App\Service;
use App\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class UnitController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function get()
    {
        return new Response(Unit::all());
    }

    public function setFile(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:manage.units,id',
            'm_file' => 'nullable|mimetypes:audio/mpeg',
            'w_file' => 'nullable|mimetypes:audio/mpeg'
        ]);
        $path = "services/".Service::currentService()->id."/units/".$request->input('unit_id');
        if($request->file('file') != null){
            $this->storeFiles($request, $path, ['m_file']);
        }
        if($request->file('w_file') != null){
            $this->storeFiles($request, $path, ['w_file']);
        }
    }
}
