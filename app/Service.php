<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed user_id
 */
class Service extends Model
{
    use SoftDeletes;

    protected $connection = "manage";

    protected $fillable = ['name','m_line','w_line','ws_username','ws_username','ws_password','ws_update_interval','user_id'];
}
