<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Symbol extends Model
{
    protected $connection = "service";

    protected $fillable = ['symbolId','is_active','m_file','w_file','unit_id','fName','eName','buyPriceFormatted','sellPriceFormatted','transPriceFormatted','buysellDiff','vol','volTick','direction','transType','dsabt'];

    protected $with = ['unit'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
