<?php
/**
 * Created by PhpStorm.
 * User: javelin
 * Date: 01.05.2017
 * Time: 10:20
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Schedule
 * @package App\Models
 * @method
 * @method static Schedule find(integer $id)
 * @method static Schedule findOrFail(integer $id)
 * @method static Schedule where($column, $condition, $special = null)
 * @method static Schedule having($column, $condition, $special = null)
 * @method static Schedule first()
 * @method static Schedule get()
 * @method static Schedule count()
 * @method static Schedule distinct()
 * @method static Schedule pluck($col)
 * @method static Schedule select($statement)
 * @method static Schedule selectRaw($statement)
 * @method static Schedule orderBy($column, $order)
 */
class Schedule extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public $timestamps = false;
    protected $table = 'schedules';
    protected $primaryKey = 'schedule_id';
    protected $fillable = [
        'worker_id',
        'day',
        'start',
        'stop',
    ];
    protected $hidden = [
        'created_at',
    ];

    public function worker()
    {
        return $this->belongsTo('App\Models\Worker');
    }
}