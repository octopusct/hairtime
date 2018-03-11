<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 27.10.2016
 * Time: 15:41
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Customer
 * @package App\Models
 * @method static Customer find(integer $id)
 * @method static Customer where($column, $condition, $special = null)
 * @method static Customer get()
 * @method static Customer first()
 * @method static Customer softDeletes()
 * @method static Customer join($table, $col1, $rule, $ol2)
 *
 */
class Customer extends Model {

    use SoftDeletes;

    public $timestamps = false;
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'logo',
    ];
    protected $dates = ['deleted_at'];

    protected $hidden = [

        'created_at',
    ];

    public function user(){
        return $this->morphOne('App\Models\User', 'entry');
    }
}