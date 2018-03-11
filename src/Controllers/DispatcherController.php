<?php
/**
 * Created by PhpStorm.
 * User: Javelin
 * Date: 15.09.2017
 * Time: 0:20
 */

namespace App\Controllers;


use App\Controllers\AdminController;
use http\Exception;
use Slim\Http\Request;
use Slim\Http\Response;
use duncan3dc\Laravel\BladeInstance;


class DispatcherController extends BaseController
{
    public function getDispatcher(Request $req, Response $res)
    {
        try {
            $ch = curl_init($req->getParam('url'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //execute request
            $result = curl_exec($ch);

            // json decode the result
            $result = @json_decode($result);

            if ($result == false) {
                throw new \Exception('Request was not correct.');
            }
        } catch (Exception $ex) {
            $result ['success'] = false;
            $result ['message'] = $ex->getMessage();
            return $result;
        }
        return $result;
    }

    public function postDispatcher(Request $req, Response $res)
    {
        try {
            $ch = curl_init($req->getParam('url'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, count($req->getParams()));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $req->getParams());
            //execute request
            $result = curl_exec($ch);

            // json decode the result
            $result = @json_decode($result);
            if ($result == false) {
                throw new \Exception('Request was not correct.');
            }


        } catch (Exception $ex) {
            $result ['success'] = false;
            $result ['message'] = $ex->getMessage();
            return $res->withJson($result);
        }
        // return $res->withJson($result);

        if ($req->getParam('request') == 'newSalon') {
            header('GET /admin/salon/18 HTTP/1.1');
            header('Host: hairtime.co.il', false);
            header('Content-Type: application/x-www-form-urlencoded', false);
            header('Cache-Control: no-cache', false);
            header('Edit=0', false);
            header('Location: https://hairtime.co.il/admin', false);

        }

    }

}