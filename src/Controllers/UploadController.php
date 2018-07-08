<?php
/**
 * Created by PhpStorm.
 * User: yemelianov
 * Date: 16.03.17
 * Time: 19:16
 */

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Salon;
use App\Models\Service;
use App\Models\ServiceWorker;
use App\Models\User;
use App\Models\Worker;
use Respect\Validation\Validator as v;
use Slim\Http\Request;
use Slim\Http\Response;

class UploadController extends BaseController
{
    function uploadFile(Request $req, Response $res)
    {

        $validation = $this->validator;
        $validation->validate($req, array(
            'user_id' => v::notEmpty()
        ));
        if ($validation->failed()) {
            return $res->withJson($validation->errors)->withStatus(400);
        }
        //$token = $req->getParam('token');
        $user_id = $req->getParam('user_id');
//        return $res->withJson(['user' =>  $user_id, 'error' =>"400", 'success' => 'false'])->withStatus(200);

        if (!isset($_FILES['uploads'])) {
            return $res->withJson([
                'message' => $this->errors['1023'],
                'error' => true,
                'status' => 'error 1023'
            ])->withStatus(400);
        }
        $files = $_FILES['uploads'];
        //return $res->withJson(['message' => $files, 'error' =>"400", 'success' => $user_id])->withStatus(200);

        if ($files['error'] == 0) {
            $name = uniqid('img-' . date('Ymd') . '-');
            //return $res->withJson(['message' => $files, 'error' =>'uploads/' . $name, 'success' => $user_id])->withStatus(200);

            if (move_uploaded_file($files['tmp_name'], 'uploads/' . $name) == true) {
                //return $res->withJson(['message' => 'loaded!', 'error' =>'uploads/' . $name, 'success' => $user_id])->withStatus(200);

                $user = User::where('user_id', $user_id)->first();
//                $user_type = $user->getEntry();
//                return $res->withJson(['user_id' => $user, 'error' =>"400", 'success' => ' ok' ])->withStatus(200);
                if ($user->entry_type == 'App\Models\Customer') {
                    $customer = Customer::where('customer_id', $user->entry_id)->first();
                    $customer->logo = $this->BASE_URL.'/api/public/uploads/' . $name;
                    $customer->save();
                    return $res->withJson([
                        'url'     => $customer->logo,
                        'message' => $this->errors['2017'],
                        'status'  => $this->errors['2011'],
                        'error'   => false])->withStatus(200);
                } elseif ($user->entry_type == 'App\Models\Salon') {
                    $salon = Salon::where('salon_id', $user->entry_id)->first();
                    $salon->logo = $this->BASE_URL.'/api/public/uploads/' . $name;
                    $salon->save();
                    return $res->withJson([
                        'url' => $salon->logo,
                        'message' => $this->errors['2017'],
                        'status'  => $this->errors['2011'],
                        'error' => false])->withStatus(200);
                } elseif ($user->entry_type == 'App\Models\Worker') {
                    $worker = Worker::where('worker_id', $user->entry_id)->first();
                    $worker->logo = $this->BASE_URL.'/api/public/uploads/' . $name;
                    $worker->save();
                    return $res->withJson([
                        'url' => $worker->logo,
                        'message' => $this->errors['2017'],
                        'status'  => $this->errors['2011'],
                        'error' => false
                    ])->withStatus(200);
                }elseif ($user->entry_type == 'App\Models\Admin') {
                    $admin = Admin::where('admin_id', $user->entry_id)->first();
                    $admin->logo = $this->BASE_URL.'/api/public/uploads/' . $name;
                    $admin->save();
                    return $res->withJson([
                        'url' => $admin->logo,
                        'message' => $this->errors['2017'],
                        'status'  => $this->errors['2011'],
                        'error' => false
                    ])->withStatus(200);
                }
                return $res->withJson([
                    'url' => $this->BASE_URL.'/api/public/uploads/' . $name,
                    'message' => $this->errors['2017'],
                    'status'  => $this->errors['2011'],
                    'error' => false
                ])->withStatus(200);
            } else {
                return $res->withJson([
                    'message' => move_uploaded_file($files['tmp_name'][0], 'uploads/' . $name),
                    'error' => true,
                    'satus' => 'error 1023',
                ])->withStatus(400);
            }
        } else {
            return $res->withJson([
                'message' => $files['error'][0],
                'error' => false,
                'success' => $user_id])
                ->withStatus(200);
        }

    }


    function uploadService(Request $req, Response $res, $args)
    {
        $user_id = $req->getParam('user_id');
        $service_id = $args['service_id'];
        $user = User::where('user_id', $user_id)->first();

        if (!isset($_FILES['uploads'])) {
            return $res->withJson([
                'message' => "No files uploaded!!",
                'status' => 'error 204',
                'error' => true])

                ->withStatus(204);
        }
        $files = $_FILES['uploads'];
        if ($files['error'] == 0) {

            if ($user->entry_type == 'App\Models\Salon') {
                $name = uniqid('salon-service-' . date('Ymd') . '-');
            } elseif ($user->entry_type == 'App\Models\Worker') {
                $name = uniqid('worker-service-' . date('Ymd') . '-');
            } elseif ($user->entry_type == 'App\Models\Customer') {
                return $res->withJson([
                    'message' => $this->errors['1024'],
                    'status' => 'error 1024',
                    'error' => true,
                ])->withStatus(403);
            }
            if (move_uploaded_file($files['tmp_name'], 'uploads/' . $name) == true) {
                //return $res->withJson(['message' => 'loaded!', 'error' =>'uploads/' . $name, 'success' => $user_id])->withStatus(200);


                //$user_type = $user->getEntry();
                //return $res->withJson(['message' => $user, 'error' =>"400", 'success' => ' ok' ])->withStatus(200);
                if ($user->entry_type == 'App\Models\Salon') {
                    $service = Service::where('salon_id', $user->entry_id)->where('service_id', $service_id)->first();
                    $service->logo = $this->BASE_URL.'/api/public/uploads/' . $name;
                    $service->save();
                    return $res->withJson([
                        'url' => $service->logo,
                        'message' => $this->errors['2017'],
                        'status'  => $this->errors['2011'],
                        'error' => false,
                    ])->withStatus(200);
                } elseif ($user->entry_type == 'App\Models\Worker') {
                    $service = ServiceWorker::where('worker_id', $user->entry_id)->where('service_id', $service_id)->first();
                    $service->logo = $this->BASE_URL.'/api/public/uploads/' . $name;
                    $service->save();
                    return $res->withJson([
                        'url' => $service->logo,
                        'message' => $this->errors['2017'],
                        'status'  => $this->errors['2011'],
                        'error' => false,
                    ])->withStatus(200);

                }
                return $res->withJson([
                    'url' => $this->BASE_URL.'/api/public/uploads/' . $name,
                    'message' => $this->errors['2017'],
                    'status'  => c,
                    'error' => false,
                ])->withStatus(200);
            } else {
                return $res->withJson([
                    'message' => $this->errors['1023'],
                    'error' => true,
                    'success' => 'error 1023'
                ])->withStatus(400);
            }
        } else {
            return $res->withJson([
                'message' => $files['error'][0],
                'error' => true,
                'status' => 'error 1025'
            ])->withStatus(200);
        }
    }
}

