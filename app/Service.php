<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed user_id
 * @property array|string|null name
 * @property array|string|null m_line
 * @property array|string|null w_line
 * @property array|string|null is_active
 * @property array|string|null ws_address
 * @property array|string|null ws_username
 * @property array|string|null ws_password
 * @property array|string|null ws_update_interval
 * @property mixed id
 */
class Service extends Model
{
    use SoftDeletes;

    protected $connection = "manage";

    protected $fillable = [
        'name','m_line','w_line','is_active','ws_username','ws_username','ws_password','ws_update_interval','user_id',
        'f_customer_welcome','f_customer_menu_start','f_customer_no_charge','f_customer_inactive','f_demo_welcome','f_demo_menu_start',
        'f_demo_no_charge','f_inactive','f_numbers'
    ];
}
