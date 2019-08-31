<?php

namespace App\Http\Controllers\Api;

use App\DefaultSymbols;
use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;
use mysqli;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if(!$this->user()->isAdmin()){
            abort(403);
        }
        $services = $this->user()->services;
        return new Response($services);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if(!$this->user()->isAdmin()){
            abort(403);
        }
        $this->validateRequest($request);
        $service = new Service();
        $service->name = $request->input('name');
        $service->m_line = $request->input('m_line');
        $service->w_line = $request->input('w_line');
        $service->is_active = $request->input('is_active');
        $service->ws_address = $request->input('ws_address');
        $service->ws_username = $request->input('ws_username');
        $service->ws_password = $request->input('ws_password');
        $service->ws_update_interval = $request->input('ws_update_interval');
        $service->user_id = $request->input('user_id');
        $service->save();
        $conn = new mysqli(config("database.connections.service.host"), config("database.connections.service.username"), config("database.connections.service.password"));
        if ($conn->connect_error) {
            abort(500);
        }
        $conn->set_charset("utf8");
        $dbname = "service-$service->id";
        $sql = "CREATE DATABASE `$dbname` CHARACTER SET utf8 COLLATE utf8_general_ci";
        if ($conn->query($sql) === TRUE) {
            Artisan::call('init',['db' => $dbname]);
        }
        return new Response($service);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);
        if(!$this->user()->isAdmin()){
            abort(403);
        }
        $defaultSymbols = DefaultSymbols::all();
        $service->setAttribute('defaultSymbols',$defaultSymbols);
        return new Response($service);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        if(!$this->user()->isAdmin()){
            abort(403);
        }
        $this->validateRequest($request, true);
        $service->update($request->only(['name','m_line','w_line','is_active','ws_address','ws_username','ws_password','ws_update_interval','user_id']));
        DefaultSymbols::truncate();
        $dss = $request->input('default_symbols');
        foreach ((array)$dss as $ds) {
            $dsm = new DefaultSymbols();
            $dsm->symbol_id = $ds['symbol_id'];
            $dsm->priority = $ds['priority'];
            $dsm->save();
        }
        $dPath = "/services/$service->id";
        $files = [];
        foreach (Service::$FILES as $FILE) {
            $files[] = "m_$FILE";
            $files[] = "w_$FILE";
        }
        $this->storeFiles($request, $dPath, $files, $service);
        $this->storeZipFiles($request, $dPath, Service::$ZIP_FILES, $service);
        $defaultSymbols = DefaultSymbols::all();
        $service = Service::findOrFail($id);
        $service->setAttribute('defaultSymbols',$defaultSymbols);
        return new Response($service);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        if(!$this->user()->isAdmin($service)){
            abort(403);
        }
        $service->delete();
        return new Response(['message' => 'deleted']);
    }

    /**
     * Validate request
     *
     * @param Request $request
     * @param bool $isUpdate
     */
    private function validateRequest(Request $request, $isUpdate = false)
    {
        $rules = [
            'name' => ['required','string','max:250'],
            'm_line' => ['required','string','max:50'],
            'w_line' => ['required','string','max:50'],
            'is_active' => ['required','boolean'],
            'ws_address' => ['required','string','max:250'],
            'ws_username' => ['required','string','max:250'],
            'ws_password' => ['required','string','max:250'],
            'ws_update_interval' => ['required','integer','max:1000000'],
            'user_id' => ['required',Rule::exists('users','id')->whereNull('deleted_at')],
            'default_symbols' => ['nullable','array'],
        ];
        $rules += [
            'default_symbols.*.symbol_id' => ['required','exists:service.symbols,id','distinct'],
            'default_symbols.*.priority' => ['required','integer','distinct']
        ];
        if($isUpdate){
            $rules += [
                'm_numbers' => ['nullable','mimes:zip'],
                'w_numbers' => ['nullable','mimes:zip']
            ];
            foreach (Service::$FILES as $FILE) {
                $rules += [
                    "m_$FILE" => ['nullable','mimetypes:audio/mpeg'],
                    "w_$FILE" => ['nullable','mimetypes:audio/mpeg'],
                ];
            }
        }
        $messages = [];
        foreach ((array)$request->input('default_symbols') as $i => $symbol) {
            $messages += [
                "default_symbols.$i.symbol_id.required" => "فیلد نماد شماره تلفن ردیف " . ($i+1) . " الزامی است",
                "default_symbols.$i.symbol_id.exists" => "فیلد نماد شماره تلفن ردیف " . ($i+1) . " الزامی است",
                "default_symbols.$i.symbol_id.distinct" => "فیلد نماد شماره تلفن ردیف " . ($i+1) . " تکراری است",
                "default_symbols.$i.priority.required" => "فیلد ترتیب پخش شماره تلفن ردیف " . ($i+1) . " الزامی است",
                "default_symbols.$i.priority.integer" => "فیلد ترتیب پخش شماره تلفن ردیف " . ($i+1) . " الزامی است",
                "default_symbols.$i.priority.distinct" => "فیلد ترتیب پخش شماره تلفن ردیف " . ($i+1) . " تکراری است",
            ];
        }
        $request->validate($rules, $messages);
    }
}
