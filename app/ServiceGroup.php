<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceGroup extends Model
{
    use SoftDeletes;

    protected $connection = "service";

    protected $guarded = ['id','deleted_at','created_at','updated_at'];

    protected $with = ['symbols'];

    public static $FILES = ['m_file','w_file'];

    public function symbols()
    {
        return $this->belongsToMany(Symbol::class);
    }
}
