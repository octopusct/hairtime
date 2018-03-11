<?php
/**
 * Created by PhpStorm.
 * User: javelin
 * Date: 11.04.2017
 * Time: 21:06
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceWorker extends Model
{
    use SoftDeletes;

    public $timestamps = false;
    protected $table = 'service_worker';
    protected $dates = ['deleted_at'];

}