<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 25.10.2016
 * Time: 15:15
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Salon
 * @package App\Models
 * @method static Salon find(integer $id)
 * @method static Salon findOrFail(integer $id)
 * @method static Salon where($column, $condition, $special = null)
 * @method static Salon having($column, $condition, $special = null)
 * @method static Salon first()
 * @method static Salon paginate($quantity)
 * @method static Salon count()
 * @method static Salon select($statement)
 * @method static Salon selectRaw($statement)
 * @method static Salon orderBy($column, $order)
 * @method static Collection get()
 */
class Salon extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $table = 'salons';

    protected $primaryKey = 'salon_id';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'first_name',
        'last_name',
        'business_name',
        'founded_in',
        'city',
        'address',
        'house',
        'lat',
        'lng',
        'phone',
        'status',
        'logo',
        'status',
    ];
    protected $hidden = [

        'created_at',
        'deleted_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function user()
    {
        return $this->morphOne('App\Models\User', 'entry');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings()
    {
        return $this->hasMany('App\Models\Rating');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function workers()
    {
        return $this->hasMany('App\Models\Worker');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services()
    {
        return $this->hasMany('App\Models\Service', 'salon_id');
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        if (this . status == 'Active') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return Collection
     */
    public function commentsWithCustomerInfo()
    {
        return $this->comments()->join('customers', 'comments.customer_id', '=', 'customers.customer_id')->where('del', false)->get(['comments.*', 'logo', 'first_name', 'last_name']);
    }
    

    /**
     * @param float $lat
     * @param float $lng
     * @param integer $radius
     * @return Collection
     */
    public static function near($lat, $lng, $radius, $devMode=false)
    {
        $formula = "(6371*acos(cos(radians({$lat}))*cos(radians(`lat`))*cos(radians(`lng`)-radians({$lng}))+sin(radians({$lat}))*sin(radians(`lat`)))) AS distance";
        $radius = ($radius + $radius * 0.1) / 1000;
        return
            $devMode ? static::selectRaw('*, ' . $formula)->having('distance', '<=', $radius)/*->where('status', 'Active')*/->orderBy('distance')->get()
                     : static::selectRaw('*, ' . $formula)->having('distance', '<=', $radius)->where('status', 'Active')->orderBy('distance')->get();
    }

}