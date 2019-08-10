<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $connection = "manage";

    protected $fillable = ['name','m_line','w_line','ws_username','ws_username','ws_password','ws_update_interval','user_id'];
}
