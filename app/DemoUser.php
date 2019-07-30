<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DemoUser extends Model
{
    use SoftDeletes;

    protected $fillable = ['time_charge','date_charge'];
}
