<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class UserController extends Controller
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
        $users = User::all();
        unset($users[0]);
        return new Response($users);
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
        $user = new User();
        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->password = bcrypt($request->input('password'));
        $user->save();
        return new Response($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        if(!$this->user()->isAdmin()){
            abort(403);
        }
        $user = User::findOrFail($id);
        return new Response($user);
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
        if(!$this->user()->isAdmin()){
            abort(403);
        }
        $user = User::findOrFail($id);
        $this->validateRequest($request, $user);
        if(!$user->isAdmin()){
            $user->name = $request->input('name');
            $user->username = $request->input('username');
            if($request->input('password')){
                $user->password = bcrypt($request->input('password'));
            }
            $user->update();
        }
        return new Response($user);
    }

    private function validateRequest(Request $request,User $user = null)
    {
        $request->validate([
            'username' => ['required','string','max:60',($user != null)?(Rule::unique('manage.users','username')->whereNull('deleted_at')->ignore($user->id)):(Rule::unique('manage.users','username')->whereNull('deleted_at'))],
            'name' => ['required','string','max:60'],
            'password' => [($user != null)?'nullable':'required','string','min:8','max:60'],
        ]);
    }
}
