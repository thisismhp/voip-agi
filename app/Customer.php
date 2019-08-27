<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property array|string|null name
 * @property array|string|null nation_code
 * @property array|string|null birth_date
 * @property array|string|null province_id
 * @property array|string|null city_id
 * @property array|string|null address
 * @property array|string|null phone_number
 * @property mixed id
 * @method static findOrFail(int $id)
 */
class Customer extends Model
{
    use SoftDeletes;

    protected $connection = "service";

    protected $fillable = ['name','nation_code','birth_date','province_id','city_id','address','phone_number','time_charge','date_charge','end_charge_checked','end_charge_comment','is_active'];

    public function numbers()
    {
        return $this->hasMany(Number::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
