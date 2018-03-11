<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 29.10.2016
 * Time: 18:43
 */

namespace App\Controllers;

use App\Models\Salon;
use Slim\Http\Request;
use Slim\Http\Response;

class SearchController extends BaseController
{

    function freeSearch(Request $req, Response $res)
    {
        $city = strtolower($req->getAttribute('city'));
        $list = Salon::where('city', 'like', '%' . $city . '%')
            //->where('status', 'Active')
            ->get()->toArray();
        $name = Salon::where('business_name', 'like', '%' . $city . '%')
            //->where('status', 'Active')
            ->get()->toArray();
        $result = array_merge_recursive($name, $list);
        return $res
            ->withJson($result, 200);
    }

    function aroundSearch(Request $req, Response $res, $args)
    {

        $lat = str_replace('.', '.', $req->getAttribute('lat'));
        $lng = str_replace('.', '.', $req->getAttribute('lng'));
        $radius = $req->getAttribute('radius');
        $list = Salon::near($lat, $lng, $radius)
            ->toArray();
        return $res->withJson($list + ['message' => 'OK', 'status' => 'success', 'error' => '']);
    }
}