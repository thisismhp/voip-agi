<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use phpseclib\Net\SSH2;
use ZipArchive;

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
        $this->setPermission();
    }

    /**
     * Store zip files
     *
     * @param Request $request
     * @param $path
     * @param $files
     * @param Model|null $model
     *
     * @return void
     */
    protected function storeZipFiles(Request $request, $path, $files, Model $model = null)
    {
        foreach ($files as $file) {
            if($request->file("$file") != null){
                config(['filesystems.default' => 'public']);
                $fName = Str::random(25).".".$request->file("$file")->getClientOriginalExtension();
                $request->file("$file")->storeAs('',$fName);
                $fPath = Str::random(25);
                try{
                    $zip = new ZipArchive();
                    $res = $zip->open("storage/$fName");
                    if($res == TRUE) {
                        $zip->extractTo("storage/$fPath");
                        $zip->close();
                        if ($model != null) {
                            $model->update(["$file" => 1]);
                        }
                        $allFiles = Storage::disk()->files($fPath);
                        foreach ($allFiles as $fileItem) {
                            $savePathArray = explode("/",$fileItem);
                            $savePath = $savePathArray[sizeof($savePathArray) - 1];
                            Storage::disk(env('FILESYSTEM_DRIVER', 'local'))->put("$path/$file/$savePath",Storage::disk()->get($fileItem));
                        }
                        Storage::disk()->deleteDirectory($fPath);
                    }
                    Storage::disk()->delete($fName);
                }catch (Exception $e){
                    Storage::disk()->delete($fName);
                }
                config(['filesystems.default' => env('FILESYSTEM_DRIVER', 'local')]);
            }
        }
        $this->setPermission();
    }

    public function setPermission()
    {
        $ssh = new SSH2(config('filesystems.disks.pbx.host'),config('filesystems.disks.pbx.port'));
        $ssh->login(config('filesystems.disks.pbx.username'), config('filesystems.disks.pbx.password'));
        $ssh->exec('chmod -Rf 777 /var/lib/asterisk/sounds/services');
        $ssh->exec('chown -Rf asterisk:asterisk /var/lib/asterisk/sounds/services');
    }
}
