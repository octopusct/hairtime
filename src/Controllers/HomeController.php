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
        $salon = Salon::find(18);
        return $res->withJson(['message' => "Connection success", 'salon'=>$salon, 'error' => '', 'success' => true])
            ->withStatus(200);
    }
}