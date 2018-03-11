<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 29.10.2016
 * Time: 15:02
 */

namespace App\Middlewares;

use App\Exceptions\TokenExpiredException;
use App\Models\Token;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;


class AuthChecker
{
    public function __invoke(Request $req, Response $res, $next)
    {
        if ($req->isOptions()){
            return $next($req, $res);
        }
        try {
            $id = $req->getParam('user_id');
            $token = $req->getParam('token');

            $token_from_DB = Token::where('token', $token)->where('user_id', $id)->firstOrFail();

            if (intval( \DateTime::createFromFormat("Y-m-d H:i:s", $token_from_DB->expires_at)
                    ->format("U")) < time()){
//                return $res->withJson(['token'=>intval( \DateTime::createFromFormat("Y-m-d H:i:s", $token_from_DB->expires_at)
//                    ->format("U")), 'rime'=>time(), '2'=>2],401);

                $tokens = Token::where('user_id', $id)->get();
                foreach ($tokens as $token){
                    $token->delete();
                }
                throw new TokenExpiredException('Token expired');
            }
        } catch (ModelNotFoundException $e) {
            return $res->withJson(['error'=>true, 'message'=>'Token expired', '1'=>1])->withStatus(401);
        } catch (TokenExpiredException $e){
            return $res->withJson(['error'=>true, 'message'=>$e->getMessage(), '2'=>2],401);
        }
        return $next($req, $res);
    }
}