<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
 * @property int value
 * @property int charge_type_id
 * @property int destination_id
 */
class DemoUserCharge extends Model
{
    protected $connection = "service";

    const UPDATED_AT = null;
}
