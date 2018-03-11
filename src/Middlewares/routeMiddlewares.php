<?php
/**
 * Created by PhpStorm.
 * User: Javelin
 * Date: 16.11.2017
 * Time: 0:16
 */

namespace App\Middlewares;


use Slim\Http\Request;
use Slim\Http\Response;

class routeMiddlewares
{

    public function __invoke(Request $req, Response $res, $next)
    {
        if ($req->isOptions()) {
            return $res
                ->withAddedHeader('Access-Control-Allow-Origin', ['http://localhost:8080'])
                ->withAddedHeader('Access-Control-Allow-Credentials', 'true')
                ->withAddedHeader('Access-Control-Allow-Methods', 'GET, POST, PUT ,DELETE, OPTIONS')
                ->withAddedHeader('Access-Control-Max-Age', '1000')
                ->withAddedHeader('Access-Control-Allow-Headers', 'Content-Type, Content-Range, Content-Disposition, Content-Description')
                ->withStatus(200);
        }
        return $next($req, $res);
    }

}