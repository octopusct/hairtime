<?php
/**
 * Created by PhpStorm.
 * User: Javelin
 * Date: 05.02.2018
 * Time: 23:21
 */

namespace App\Exceptions;


use Exception;
use Throwable;

class TokenExpiredException extends Exception
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}