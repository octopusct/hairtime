<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 25.10.2016
 * Time: 12:18
 */

namespace App\Controllers;

use App\Models\Salon;
use DateTime;
use DateTimeZone;
use Slim\Http\Request;
use Slim\Http\Response;

class HomeController extends BaseController
{
    function __invoke(Request $req, Response $res, $args)
    {
        return $res->withJson([
            'message' => $this->messages['2009'],
            'error' => $this->PREFIX,
            'error1' => $this->BASE_URL.'/'.$this->PREFIX,
            'success' => true])
            ->withStatus(200);
    }
}