<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 27.10.2016
 * Time: 7:25
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Token
 * @package App\Models
 * @method static Token find(integer $id)
 * @method static Token findOrFail(integer $id)
 * @method static Token where($column, $condition, $special = null)
 * @method static Token first()
 * @method static Token firstOrFail()
 */
class Token extends Model{

    use SoftDeletes;

    public $timestamps = false;
    protected $primaryKey = 'token_id';
    protected $table = 'tokens';
    protected $fillable = [
        'token',
        'user_id',
        'expires_at',
    ];
    protected $hidden = [
        'created_at',
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public static function deleteAll($user_id)
    {
        static::where('user_id', $user_id)->delete();
    }

    public static function deleteOne($user_id, $token)
    {
        static::where('token', $token)->where('user_id', $user_id)->delete();
    }

//    public function createNew(array $attributes)
//    {
////        $next_week = date("Y-m-d H:i:s")+(7*24*60*60);
////        $attributes['expires_at'] = date("Y-m-d H:i:s")+(7*24*60*60);
//        $attributes['expires_at'] = '2018-02-09 13:53:05';
//        return $attributes;
//        parent::create($attributes);
//        return;
//    }
}