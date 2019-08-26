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

    protected $guarded = ['id','deleted_at','created_at','updated_at'];

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
