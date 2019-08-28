<?php

namespace App\Http\Controllers\Api;

use App\Service;
use App\ServiceGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ServiceGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return new Response(ServiceGroup::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);
        $serviceGroup = new ServiceGroup();
        $serviceGroup->name = $request->input('name');
        $serviceGroup->is_active = $request->input('is_active');
        $serviceGroup->save();
        $serviceGroup->symbols()->sync($this->symbolsArray($request->input('symbols')));
        $dPath = "services/".Service::currentService()->id."/service-groups/$serviceGroup->id";
        $this->storeFiles($request, $dPath, ServiceGroup::$FILES, $serviceGroup);
        return new Response($serviceGroup);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $serviceGroup = ServiceGroup::findOrFail($id);
        return new Response($serviceGroup);
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
        $this->validateRequest($request);
        $serviceGroup = ServiceGroup::findOrFail($id);
        $serviceGroup->update($request->only(['name','is_active']));
        $serviceGroup->symbols()->sync($this->symbolsArray($request->input('symbols')));
        $dPath = "services/".Service::currentService()->id."/service-groups/$serviceGroup->id";
        $this->storeFiles($request, $dPath, ServiceGroup::$FILES, $serviceGroup);
        $serviceGroup = ServiceGroup::findOrFail($id);
        return new Response($serviceGroup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $serviceGroup = ServiceGroup::findOrFail($id);
        $serviceGroup->delete();
        return new Response(['message' => 'deleted']);
    }

    private function symbolsArray($symbols)
    {
        $exp = array();
        foreach ((array)$symbols as $symbol) {
            $exp[$symbol['id']] = array('priority' => $symbol['priority']);
        }
        return $exp;
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:250'],
            'm_file' => ['nullable','mimetypes:audio/mpeg'],
            'w_file' => ['nullable','mimetypes:audio/mpeg'],
            'is_active' => ['required','boolean'],
            'symbols' => ['nullable','array'],
            'symbols.*.id' => ['required','exists:service.symbols,id','distinct'],
            'symbols.*.priority' => ['required','integer','distinct']
        ]);
    }
}
