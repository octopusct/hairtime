<?php
/**
 * Created by PhpStorm.
 * User: javelin
 * Date: 22.05.2017
 * Time: 0:44
 */

namespace App\Controllers;


use App\Models\Notification;
use App\Models\NToken;
use Slim\Http\Request;
use Slim\Http\Response;

class NotificationController extends BaseController
{
    public function setToken(Request $req, Response $res)
    {
        $ntoken = new NToken();
        $ntoken->n_token = $req->getParam('n_token');
        $ntoken->user_id = $req->getParam('user_id');
        $ntoken->save();
        return $res->withJson($ntoken)->withStatus(201);

    }

    public function getNotification(Request $req, Response $res, $args)
    {
        $notification = Notification::where('user_id', $args['user_id'])
            ->where('status', false)
            ->get()
            ->toArray();
        return $res->withJson($notification+[
                'message'=> count($notification)>0 ? 'OK' : $this->messages['2010'],
                'status'=>'success',
                'error'=>false
            ], 200);
    }

    public function setStatus(Request $req,  Response $res)
    {
        $result = Notification::find($req->getParam('notification_id'))->update(['status'=>true]);
        if ($result) {
            $answer = ['message'=>'OK', 'status'=>$this->messages['2011'], 'error'=>''];
        }else{
            $answer = [
                'message'=> $this->errors['1014'],
                'status'=>'error',
                'error'=>false
            ];
        }
        return $res->withJson($answer, 200);
    }
    
    public function tryNotification(Request $req, Response $res)
    {
        $ntoken = NToken::where('user_id', $req->getParam('user_id'))->pluck('n_token');

        //return $res->withJson($ntoken)->withStatus(200);
        $message = array('message' => $this->messages['2012']);

        $notification = new Notification();
        $result = $notification->send_notifications($ntoken, $message);
        return $res->withJson($result)->withStatus(200);
    }

}