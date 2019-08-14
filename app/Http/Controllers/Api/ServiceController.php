<?php

namespace App\Http\Controllers\Api;

use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    /**
     * ServiceController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Service::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $services = $this->user()->services;
        return new Response($services);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
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
        $dPath = "/services/$service->id";
        $this->storeFiles($request, $dPath, [
            'f_customer_welcome','f_customer_menu_start','f_customer_no_charge','f_customer_inactive',
            'f_demo_welcome','f_demo_menu_start', 'f_demo_no_charge','f_inactive']);
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
        return new Response($service);
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
        $service = Service::findOrFail($id);
        return new Response([$service,$request]);
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
        $service->delete();
        return new Response(['message' => 'deleted']);
    }

    /**
     * Validate request
     *
     * @param Request $request
     * @param bool $isUpdate
     * @throws ValidationException
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
            'f_customer_welcome' => [($isUpdate)?'nullable':'required','mimetypes:audio/mpeg'],
            'f_customer_menu_start' => [($isUpdate)?'nullable':'required','mimetypes:audio/mpeg'],
            'f_customer_no_charge' => [($isUpdate)?'nullable':'required','mimetypes:audio/mpeg'],
            'f_customer_inactive' => [($isUpdate)?'nullable':'required','mimetypes:audio/mpeg'],
            'f_demo_welcome' => [($isUpdate)?'nullable':'required','mimetypes:audio/mpeg'],
            'f_demo_menu_start' => [($isUpdate)?'nullable':'required','mimetypes:audio/mpeg'],
            'f_demo_no_charge' => [($isUpdate)?'nullable':'required','mimetypes:audio/mpeg'],
            'f_inactive' => [($isUpdate)?'nullable':'required','mimetypes:audio/mpeg'],
//            'f_numbers' => [($isUpdate)?'nullable':'required','mimes:zip']

        ];
        $request->validate($rules);
        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            throw $e;
        }
    }
}
