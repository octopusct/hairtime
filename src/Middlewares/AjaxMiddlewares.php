<?php
/**
 * Created by PhpStorm.
 * User: Javelin
 * Date: 09.02.2018
 * Time: 2:09
 */

namespace App\Middlewares;


use App\Exceptions\TokenExpiredException;
use App\Models\Admin;
use App\Models\Token;
use duncan3dc\Laravel\BladeInstance;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class AjaxMiddlewares
{
    function __invoke(Request $req, Response $res, $next)
    {
        try {
            $id = $req->getHeader('User-ID')[0];
            $token = $req->getHeader('Token')[0];
            $admin = Admin::where('entry_id', $id)->firstOrFail();
            $token = Token::where('token', $token)->where('user_id', $id)->firstOrFail();

            if ( intval(\DateTime::createFromFormat("Y-m-d H:i:s", $token->expires_at)
                    ->format("U")) <= time()){
                $token->delete();
                throw new TokenExpiredException('Token expired, please re-login.');
            }
//            return $res->withJson(['admin'=>$admin, 'token'=>$token], 200);

        } catch (ModelNotFoundException $e) {
            return $res->withJson(['message'=>$e->getMessage(), 'error'=>401, 'success' => false], 401);
        } catch (TokenExpiredException $e){
            return $res->withJson(['message'=>$e->getMessage(), 'error'=>401, 'success' => false], 401);
        }
        return $next($req, $res);
    }
}