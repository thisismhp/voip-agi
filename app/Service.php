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
 * @property array|string|null ws_update_at
 * @property mixed id
 */
class Service extends Model
{
    use SoftDeletes;

    protected $connection = "manage";

    protected $guarded = ['id','deleted_at','created_at','updated_at'];

    public static $FILES = ['customer_welcome','customer_menu_start','customer_no_charge','customer_inactive', 'demo_welcome','demo_menu_start','demo_no_charge','inactive','sb','opr','no_charge_sms','no_charge_opr'];

    public static $ZIP_FILES = ['m_numbers','w_numbers'];

    /**
     * Return current service
     *
     * @return mixed
     */
    public static function currentService()
    {
        $serviceID = request()->header('serviceid');
        return self::find($serviceID);
    }
}
