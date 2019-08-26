<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Get authenticated user
     *
     * @return Authenticatable|null
     */
    protected function user(){
        return auth()->user();
    }

    /**
     * Store request files
     *
     * @param Request $request
     * @param $path string
     * @param $files array
     * @param Model $model
     * @return void
     */
    protected function storeFiles(Request $request, $path, $files, Model $model = null)
    {
        foreach ($files as $file) {
            if($request->file("$file") != null){
                $request->file("$file")->storeAs($path,$file.".".$request->file("$file")->getClientOriginalExtension());
                if($model != null){
                    $model->update(["$file" => 1]);
                }
            }
        }
    }

    protected function storeZipFiles(Request $request, $path, $files, Model $model = null)
    {
        foreach ($files as $file) {
            if($request->file("$file") != null){
                config(['filesystems.default' => 'public']);
                $fName = Str::random(25).".".$request->file("$file")->getClientOriginalExtension();
                $request->file("$file")->storeAs('',$fName);
                config(['filesystems.default' => env('FILESYSTEM_DRIVER', 'local')]);
                //TODO extract and move to pbx server
                if($model != null){
                    $model->update(["$file" => 1]);
                }
            }
        }
    }
}
