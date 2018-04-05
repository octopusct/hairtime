<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 29.10.2016
 * Time: 16:45
 */
namespace App\Middlewares;

use App\Models\User;
use Exception;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 *Class PermissionChecker
 * check permissions fro user's role
 *
 */
class PermissionChecker
{
    protected $role = [];
    protected $roles = [
        'customer',
        'salon',
        'worker',
        'admin'
    ];

    function __construct( $incomig_roles)
    {
        $res = new Response();
        try{
            foreach ($incomig_roles as $incoming_role) {
                if (!in_array($incoming_role, $this->roles))
                    throw new Exception('Wrong role');
            }
            $this->role = $incomig_roles;

        }catch(Exception $e){
            return $res->withJson(['message'=> $e->getMessage()], 403);
        }
    }


    function __invoke(Request $req, Response $res, $next)
    {
        if ($req->isOptions()){
            return $next($req, $res);
        }
//        return $res->withJson(['in_roles'=> $this->role, 'error'=>true],400);
        $id = $req->getParam('user_id');
//        return $res->withJson(['in_roles'=> $this->role, 'id'=> $id, 'error'=>true],400);

        $user = User::find($id);

        $user_role = mb_strtolower (explode("\\", $user->entry_type)[2]);

        if (in_array($user_role, $this->role)){
            return $next($req, $res);
        }else{
            return $res->withStatus(403)->withJson(['message'=> 'Permission Errorttt', 'error'=>true, 'status'=>403], 403);
        }
    }
}