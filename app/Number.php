<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed is_active
 * @property mixed charge_type_id
 * @property mixed phone_number_type_id
 * @property mixed customer_id
 * @property mixed phone_number
 */
class Number extends Model
{
    use SoftDeletes;

    protected $connection = "service";

    protected $fillable = ['phone_number_type_id','phone_number','charge_type_id','is_active'];
}
