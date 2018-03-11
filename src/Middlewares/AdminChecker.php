<?php
/**
 * Created by PhpStorm.
 * User: Javelin
 * Date: 02.10.2017
 * Time: 22:36
 */

namespace App\Middlewares;


use App\Exceptions\TokenExpiredException;
use App\Models\Admin;
use App\Models\Token;
use duncan3dc\Laravel\BladeInstance;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class AdminChecker
{
    function __invoke(Request $req, Response $res, $next)
    {
        try {
//            session_start();
            $id = $req->getHeader('User-ID')[0];
            $token = $req->getHeader('Token')[0];
            $admin = Admin::where('entry_id', $id)->firstOrFail();
            $token = Token::where('token', $token)->where('user_id', $id)->firstOrFail();
//            return $res->withJson(['admin'=>intval(\DateTime::createFromFormat("Y-m-d H:i:s", $token->expires_at)
//                ->format("U")), 'token'=>time()], 200);

            if ( intval(\DateTime::createFromFormat("Y-m-d H:i:s", $token->expires_at)
                ->format("U")) > time()){
                $token->deleteOne($id, $token);
                throw new TokenExpiredException('Token expired, please re-login.');
            }
            return $res->withJson(['admin'=>$admin, 'token'=>$token], 200);

        } catch (ModelNotFoundException $e) {
            $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
            echo $blade->render("login");
            return;
        } catch (TokenExpiredException $e){
            $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
            echo $blade->render("login", [
                'error' => $e->getMessage(),
            ]);
            return;
        }
        return $next($req, $res);
    }
}