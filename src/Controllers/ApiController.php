<?php
/**
 * Created by PhpStorm.
 * User: Javelin
 * Date: 02.11.2017
 * Time: 21:29
 */

namespace App\Controllers;


use App\Models\Admin;
use duncan3dc\Laravel\BladeInstance;
use Slim\Http\Request;
use Slim\Http\Response;

class ApiController extends BaseController
{
    public function api(Request $req, Response $res)
    {
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        if (isset($_SESSION['user_id'])) {
            $admin = Admin::where('entry_id', $_SESSION['user_id'])->first();
            echo $blade->render("api", [
                'admin' => $admin,
                'menu' => 'api'
            ]);
        }
    }


}