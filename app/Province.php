<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $connection = "manage";

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
