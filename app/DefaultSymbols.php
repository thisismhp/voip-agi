<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefaultSymbols extends Model
{
    protected $connection = "service";

    public const CREATED_AT = null;

    public const UPDATED_AT = null;

    protected $guarded = [];
}
