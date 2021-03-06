<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 15.10.2016
 * Time: 22:35
 */

namespace App\Controllers; // name declaration

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Key;
use App\Models\Salon;
use App\Models\Token;
use App\Models\User;
use App\Models\Worker;
use App\Models\Queue;
use duncan3dc\Laravel\BladeInstance;
use FreakMailer;
use PHPMailer;
use phpmailerException;
use Respect\Validation\Validator as v;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Controllers\EmailController as EmailController;


class AuthController extends BaseController
{


    public function confirmEmail(Request $req, Response $res)
    {
        $user_id = $req->getAttribute('user_id');
        //$user = User::find($user_id)->first();
        $user = User::where('user_id', $user_id)->first();
        // return $res->withJson(['message' => $user, 'error' => "", 'success' //=> $user_id])->withStatus(200);

        $user->confirm_email = true;
        $user->save();
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");

        echo $blade->render("email");
        return;
        //return $res->withJson(['message' => $user, 'error' => "", 'success' => $user_id])->withStatus(200);
    }


    public function singupCustomer(Request $req, Response $res)
    {

        $validation = $this->validator;
        $validation->validate($req, array(
            'email' => v::notEmpty()->email()->length(5, 255),
            'first_name' => v::noWhitespace()->notEmpty()->length(1, 100),
            'last_name' => v::noWhitespace()->notEmpty()->length(1, 100),
            'password' => v::notEmpty()->length(1, 50), //TODO: turn off debug mode
            'phone' => v::phone(),
            'logo' => v::optional(v::url()->length(1, 100))
        ));
        if ($validation->failed()) {
            $i=0; $result= Array();
            foreach ($validation->errors as $error){
                $result[$i]= $error;
                $i++;
            }
            $this->logger->info('Validation error', array('MESSAGE'=>$result, 'REQUEST'=>$req->getParams()));
            return $res->withJson([
                'message'=> $result,
                'status'=>'error 1002',
                'error'=>true])->withStatus(400);
        }

        if (User::where('email', $req->getParam('email'))->count() > 0) {
            return $res->withJson([
                'message' => $this->errors['1001'],
                'status' => 'error 1001',
                'error' => true
            ])->withStatus(400);
        }

        $token= $this->makeToken();
        $customer = Customer::create($req->getParams());
        $user_params = $req->getParams();
        if (!$this->config['devMode']){
            $user_params['password'] =  password_hash($req->getParam('password'), PASSWORD_DEFAULT);
        }

        $user = $customer->user()->create($user_params );
        $user->tokens()->create([
            'token' => $token,
            'expires_at'=> date("Y-m-d H:i:s", time()+(52*7*24*60*60))
        ]);
        $confirm = $user->user_id;

        $mail = new EmailController ();

        $user_name = $req->getParam('last_name') . " " . $req->getParam('first_name');
        $mail->AddAddress($req->getParam('email'), $user_name); // Получатель

        $letter = file_get_contents(__DIR__ . '/../letters/singup_customer.html');
        $title = explode('title', $letter)[1];
        $title = substr($title, 1, strlen($title)-3);
        $mail->Subject = htmlspecialchars($title);  // Тема письма
        if ($letter) {
            $letter_body = sprintf($letter, $this->BASE_URL.'/'.$this->PREFIX, $confirm, $confirm);
            $mail->MsgHTML($letter_body); // Текст сообщения
            $mail->AltBody = "Dear " . $user_name . ", confirm your email, please. Copy next string to your browser and press enter: https://hairtime.co.il/auth/confirm_email/" . $confirm;
            $result = $mail->Send();
            /* $login_data = ['user_id' => $user->user_id, 'token' => $token, 'type' => explode('\\', get_class($user->getEntry()))[2],
                 'confirm_email'=> $user->confirm_email];
             return $res->withJson($user->getEntry()->toArray() + $login_data);*/
            $type = mb_strtolower(explode("\\", $user->entry_type)[2]);

            if ($result) {

                return $res->withJson($user->getEntry()->toArray() + [
                    'user_id' => $user->user_id,
                    'token' => $token,
                    $type . '_id' => $user->entry_id,
                    'confirm_email' => '0',
                    'email-status' => $this->locale['messages']['2002'],
                    'message' => 'Ok',
                    'status' => 'sucess',
                    'error' => false,
                ])->withStatus(201);
            }
        }

        return $res->withJson($user->getEntry()->toArray() + ['user_id' => $user->user_id, 'token' => $token, $type . '_id' => $user->entry_id,
                'confirm_email' => '0',
                'message' => $mail->ErrorInfo,
                'status' => 'error',
                'error' => true,
        ])->withStatus(400);
    }

    public function editCustomer(Request $req, Response $res)
    {
        $validation = $this->validator;
        $validation->validate($req, array(
            'first_name' => v::noWhitespace()->notEmpty()->length(1, 100),
            'last_name' => v::noWhitespace()->notEmpty()->length(1, 100),
            'phone' => v::phone(),
        ));
        if ($validation->failed()) {
            $i=0; $result= Array();
            foreach ($validation->errors as $error){
                $result[$i]= $error;
                $i++;
            }
            // $this->logger->info('Validation error', array('MESSAGE'=>$result, 'REQUEST'=>$req->getParams()));
            return $res->withJson([
                'message'=>$this->errors['1002'],
                'status'=>'error 1002',
                'error'=>$result
            ])->withStatus(400);
        }
        $user = User::where('user_id', $req->getParam('user_id'))->first();
        //return $res->withJson($user, 200);
        $customer = Customer::where('customer_id', $user->entry_id)->first();
        $customer->first_name = $req->getParam('first_name');
        $customer->last_name = $req->getParam('last_name');
        $customer->phone = $req->getParam('phone');
        //$customer->password = $req->getParam('password');
        $customer->save();
        return $res->withJson($customer->toArray())
            ->withStatus(201)
            ;

    }

    public function singupSalon(Request $req, Response $res)
    {
        $validation = $this->validator;
        $validation->validate($req, array(
            'email' => v::notEmpty()->email()->length(5, 255)->EmailNotUsed(),
            'first_name' => v::notEmpty()->noWhitespace()->length(1, 100),
            'last_name' => v::notEmpty()->noWhitespace()->length(1, 100),
            'business_name' => v::notEmpty()->length(1, 100),
            'founded_in' => v::between(1980, date("Y")),
            'city' => v::notEmpty()->length(1, 255),
            'address' => v::notEmpty()->length(1, 255),
            'house' => v::notEmpty()->length(1, 10),
            /*'lat' => v::notEmpty(),
            'lng' => v::notEmpty(), //TODO: regex validator*/
            'password' => v::notEmpty()->length(1, 50), //TODO: turn off debug mode
            'phone' => v::phone(),
            'logo' => v::optional(v::url()->length(1, 100)),
            // 'activation_key' => v::notEmpty()->length(1, 100)->keyExists()
        ));
        if ($validation->failed()) {
            $i=0; $result= Array();
            foreach ($validation->errors as $error){
                $result[$i]= $error;
                $i++;
            }
            // $this->logger->info('Validation error', array('MESSAGE'=>$result, 'REQUEST'=>$req->getParams()));
            return $res->withJson([
                'message'=> $this->errors['1002'],
                'status'=>'error 1002',
                'error' => true
            ])->withStatus(400);
        }
        if (User::where('email', $req->getParam('email'))->count() > 0) {
            return $res->withJson([
                'message' => $this->errors['1001'],
                'status' => 'error 1001',
                'error' => true
            ])
                ->withStatus(400)
                ;
        }
        //Key::where('key_body', $req->getParam('activation_key'))->first()->delete();

        $token = $this->makeToken();

        $salon = Salon::create($req->getParams());
        $user_params = $req->getParams();
        if (!$this->config['devMode']){
            $user_params['password'] =  password_hash($req->getParam('password'), PASSWORD_DEFAULT);
        }
//        $user = $customer->user()->create($user_params );

        $user = $salon->user()->create($user_params);
        $user->tokens()->create([
            'token' => $token,
            'expires_at'=> date("Y-m-d H:i:s", time()+(52*7*24*60*60))
        ]);

        $confirm = $user->user_id;

        $mail = new EmailController();

        $user_name = $req->getParam('last_name') . " " . $req->getParam('first_name');
        $mail->AddAddress($req->getParam('email'), $user_name); // Получатель
//        $mail->Subject = htmlspecialchars(' אימות כתובת המייל שלך ב ' . ' /  Verify e-mail address, please');  // Тема письма
        $letter = file_get_contents(__DIR__ . '/../letters/singup_salon.html');
        $title = explode('title', $letter)[1];
        $title = substr($title, 1, strlen($title)-3);
        $mail->Subject = htmlspecialchars($title);  // Тема письма
        if ($letter) {
            $letter_body = sprintf($letter, $this->BASE_URL.'/'.$this->PREFIX, $confirm, $confirm);
            $mail->MsgHTML($letter_body); // Текст сообщения
            $mail->AltBody = "Confirm your email, please. Copy next string to your browser and press enter: ".$this->BASE_URL."/auth/confirm_email/" . $confirm;
            $result = $mail->Send();

            if ($result) {
                return $res->withJson($user->getEntry()->toArray() + [
                    'user_id' => $user->user_id,
                    'email' => $user->email,
                    'salon_id' => $user->entry_id,
                    'token' => $token,
                    'confirm_email' => '0',
                    'email-status' => $this->messages['2002'],
                    'message' => 'Ok',
                    'status' => 'success',
                    'error' => false,

                ])->withStatus(201);
            }
        }

        return $res->withJson($user->getEntry()->toArray() + [
            'user_id' => $user->user_id,
            'salon_id' => $user->entry_id,
            'token' => $token,
            'confirm_email' => '0',
            'email-status' => $mail->ErrorInfo,
            'message' => 'Ok',
            'status' => 'error',
            'error' => false,
        ])->withStatus(201);

    }

    public function editSalon(Request $req, Response $res)
    {
        $validation = $this->validator;
        $validation->validate($req, array(
            'email' => v::notEmpty()->email()->length(5, 255)->EmailNotUsed(),
            'first_name' => v::notEmpty()->noWhitespace()->length(1, 100),
            'last_name' => v::notEmpty()->noWhitespace()->length(1, 100),
            'business_name' => v::notEmpty()->length(1, 100),
            'founded_in' => v::between(1980, date("Y")),
            'city' => v::notEmpty()->length(1, 255),
            'address' => v::notEmpty()->length(1, 255),
            'house' => v::notEmpty()->length(1, 10),
            //'lat' => v::notEmpty(),
            //'lng' => v::notEmpty(),
//            'password' => v::notEmpty()->length(1, 50),
            'phone' => v::phone(),
            'logo' => v::optional(v::url()->length(1, 100)),
            // 'activation_key' => v::notEmpty()->length(1, 100)->keyExists()
        ));
        if ($validation->failed()) {
            $i=0; $result= Array();
            foreach ($validation->errors as $error){
                $result[$i]= $error;
                $i++;
            }
            // $this->logger->info('Validation error', array('MESSAGE'=>$result, 'REQUEST'=>$req->getParams()));
            return $res->withJson([
                'message'=>$this->errors['1002'],
                'status'=>'error 1002',
                'error'=>$result
            ])->withStatus(400);
        }

        //Key::where('key_body', $req->getParam('activation_key'))->first()->delete();
        $user = User::where('email', $req->getParam('email'))->first();
        $user->update($req->getParams());
        //return $res->withJson(['sdgasdg'=>$user->user_id])->withStatus(201);
        $salon = Salon::where('salon_id', $user->entry_id)->first();
        $salon->update($req->getParams());
        return $res->withJson($salon->toArray()+[
            'message' => 'Ok',
            'error' => false,
            'user_id' => $user->user_id,
            'token' => $req->getParam('token'),
            'email' => $user->email,
            'confirm_email' => '0'])
            ->withStatus(201)
            ;

    }

    public function forgotPassword(Request $req, Response $res, $args)
    {
        if (User::where('email', $args['email'])->count() > 0) {
            $user = User::where('email', $args['email'])->first();
            $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
            $max = 8;
            $password = null;
            while ($max--)
                $password .= $chars[rand(0, 61)];
            $user->password = $this->config['devMode'] ? $password : password_hash($password, PASSWORD_DEFAULT);
            $user->save();

            $mail = new EmailController();

            $user_name = $user->last_name . " " . $user->first_name;
            $mail->AddAddress($args['email'], $user_name); // Получатель
//            $mail->Subject = htmlspecialchars('New password for HairTime application');  // Тема письма
            $letter = file_get_contents(__DIR__ . '/../letters/forgot_pass.html');
            $title = explode('title', $letter)[1];
            $title = substr($title, 1, strlen($title)-3);
            $mail->Subject = htmlspecialchars($title);  // Тема письма
            if ($letter) {
                $letter_body = sprintf($letter, $this->BASE_URL.'/'.$this->PREFIX, $user_name, $password);
                $mail->MsgHTML($letter_body); // Текст сообщения
                $mail->AltBody = "Dear " . $user_name . ", temporary password for your account in HairTime application is: " . $password;
                $result = $mail->Send();
                if ($result) {
                    return $res->withJson([
                        'message' => $this->messages['2003'],
                        'error' => null
                    ])->withStatus(200);
                }
            return $res->withJson([
                'message' => $this->errors['1007'].' '.$this->errors['1008'],
                'status' => 'error 1008',
                'error' => '520',
            ])->withStatus(520);
            }
        }
        return $res->withJson([
            'message' => $this->errors['1009'],
            'error' => "error 1009",
            'error' => "404"
        ])->withStatus(404);

    }

    public function startWorker(Request $req, Response $res)
    {
        $validation = $this->validator;
        $validation->validate($req, [
            'email' => v::notEmpty()->email()->length(5, 255)->EmailNotUsed(),
            'salon_id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            $i=0; $result= Array();
            foreach ($validation->errors as $error){
                $result[$i]= $error;
                $i++;
            }
            // $this->logger->info('Validation error', array('MESSAGE'=>$result, 'REQUEST'=>$req->getParams()));
            return $res->withJson([
                'message'=>$result,
                'status'=>400,
                'error'=>true], 400);
        }
        if (User::where('email', $req->getParam('email'))->count() > 0) {
            return $res->withJson([
                'message' => $this->errors['1001'],
                'status'=>'error 1001',
                'error' => true], 400);
        }
        //$token = $this->makeToken();
        $salon = Salon::where('salon_id', $req->getParam('salon_id'))->first();
        $worker = $salon->workers()->create(['email' => $req->getParam('email')]);
        //$worker = Worker::create($req->getParams());
        $user = $worker->user()->create($req->getParams());
        $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
        $max = 8;
        $password = null;
        while ($max--)
            $password .= $chars[rand(0, 61)];
        $user->password = $this->config['devMode'] ? $password : password_hash($password, PASSWORD_DEFAULT);
        $user->confirm_email = false;
        $user->save();

        //$confirm = $user->user_id;

        $mail = new EmailController();

        $user_name = $req->getParam('last_name') . " " . $req->getParam('first_name');
        $user_email = $req->getParam('email') ;
        $mail->AddAddress($req->getParam('email'), $user_name); // Получатель
//        $mail->Subject = htmlspecialchars('Salon ' . $salon->business_name . ' added you as HairMaster');  // Тема письма
        $letter = file_get_contents(__DIR__ . '/../letters/start_worker.html');
        $title = explode('title', $letter)[1];
        $title = substr($title, 1, strlen($title)-3);
        $mail->Subject = htmlspecialchars($title);  // Тема письма
        if ($letter){
            $letter_body = sprintf($letter, $this->BASE_URL.'/'.$this->PREFIX, $user_name, $user_email, $password);
            $mail->MsgHTML($letter_body); // Текст сообщения
            $mail->AltBody = "Dear " . $user_name . ", confirm your email, please. Copy next string to your browser and press enter: https://play.google.com/apps/testing/com.haduken.hairtime";
            $result = $mail->Send();

            if ($result) {
                return $res->withJson([
                    'user_id' => $user->user_id,
                    'worker_id' => $user->entry_id,
                    'confirm_email' => '0',
                    'email-status' => $this->messages['2002'],
                    'message'=>'Ok',
                    'status' => 'success',
                    'error' => false,
                ])->withStatus(201);
            }
        }
        return $res->withJson([
            'user_id' => $user->user_id,
            'worker_id' => $user->entry_id,
            'confirm_email' => '0',
            'message' => $mail->ErrorInfo,
            'status' => 'error',
            'error' => true,
        ])->withStatus(400);

    }

    public function singupWorker(Request $req, Response $res)
    {
        $validation = $this->validator;
        $validation->validate($req, [
            'email' => v::notEmpty()->email()->length(5, 255),
            'first_name' => v::noWhitespace()->notEmpty()->length(1, 100),
            'last_name' => v::noWhitespace()->notEmpty()->length(1, 100),
            'specialization' => v::notEmpty()->length(1, 100),
            'start_year' => v::between(1980, date("Y")),
            //'password' => v::length(1, 50), //TODO: turn off debug mode
            //'salon_id' => v::notEmpty(),
            'phone' => v::phone(),
            'logo' => v::optional(v::url()->length(1, 100))
        ]);
        if ($validation->failed()) {
            $i=0; $result= Array();
            foreach ($validation->errors as $error){
                $result[$i]= $error;
                $i++;
            }
            // $this->logger->info('Validation error', array('MESSAGE'=>$result, 'REQUEST'=>$req->getParams()));
            return $res->withJson([
                'message'=>$this->errors['1002'],
                'status'=>'error 1002',
                'error'=>$result
            ])->withStatus(400);
        }

        $token = $this->makeToken();
        $user = User::where('email', $req->getParam('email'))->first();
        if ($user == null) {
            return $res->withJson([
                'message' => $this->errors['1010'],
                'status' => 'error 1010',
                'error' => '404'
            ])->withStatus(404);
        }
        $user->update($req->getParams()+['confirm_email' => true]);
        // $user->confirm_email = true;
        // $user->password = $req->getParam('password');
        // $user->save();
        $user->tokens()->create([
            'token' => $token,
            'expires_at'=> date("Y-m-d H:i:s", time()+(52*7*24*60*60))
        ]);
        $worker = Worker::where('worker_id', $user->entry_id)->first();
        $salon = $worker->salon;
        // $worker->first_name = $req->getParam('first_name');
        // $worker->last_name = $req->getParam('last_name');
        // $worker->specialization = $req->getParam('specialization');
        // $worker->start_year = $req->getParam('start_year');
        // $worker->phone = $req->getParam('phone');
        // $worker->logo = $req->getParam('logo');
        // $worker->save();
        $worker->update($req->getParams());
        $salon->staff_number = $salon->staff_number + 1;
        $salon->save();
        return $res->withJson($worker->toArray() + $user->toArray())
            ->withStatus(201);
    }

    public function editWorker(Request $req, Response $res, $args)
    {
        $validation = $this->validator;
        $validation->validate($req, [
//            'email' => v::notEmpty()->email()->length(5, 255),
            'first_name' => v::noWhitespace()->notEmpty()->length(1, 100),
            'last_name' => v::noWhitespace()->notEmpty()->length(1, 100),
            'specialization' => v::notEmpty()->length(1, 100),
            'start_year' => v::notEmpty()->length(1, 2),
//            'password' => v::notEmpty()->length(1, 50),
            //'salon_id' => v::notEmpty(),
            'phone' => v::phone(),
            //'logo' => v::optional(v::url()->length(1, 100))
        ]);
        if ($validation->failed()) {
            $i=0; $result= Array();
            foreach ($validation->errors as $error){
                $result[$i]= $error;
                $i++;
            }
            // $this->logger->info('Validation error', array('MESSAGE'=>$result, 'REQUEST'=>$req->getParams()));
            return $res->withJson([
                'message'=> $this->errors['1002'],
                'status'=>'error 1002',
                'error'=>$result
            ])->withStatus(400);
        }
        if ($args['worker_id']){
            $worker = Worker::where('worker_id', $args['worker_id'])->first();
        }else{
            $user = User::where('email', $req->getParam('email'))->first();
            $worker = Worker::where('worker_id', $user->entry_id)->first();
        }
        $worker->update($req->getParams());
//        $worker->first_name = $req->getParam('first_name');
//        $worker->last_name = $req->getParam('last_name');
//        $worker->specialization = $req->getParam('specialization');
//        $worker->start_year = $req->getParam('start_year');
//        $worker->phone = $req->getParam('phone');
//        $worker->password = $req->getParam('password');
//        $worker->save();

        return $res->withJson($worker)

            ->withStatus(201);

    }

    public function singin(Request $req, Response $res)
    {
        $validation = $this->validator;
        $validation->validate($req, array(
            'email' => v::notEmpty()->email()->length(5, 255),
            'password' => v::notEmpty()->length(1, 50) //TODO: turn off debug mode
        ));
        if ($validation->failed()) {
            $i=0; $result= Array();
            foreach ($validation->errors as $error){
                $result[$i]= $error;
                $i++;
            }
            $this->logger->info('Validation error', array('MESSAGE'=>$result, 'REQUEST'=>$req->getParams()));
            return $res->withJson([
                'message'=> $this->errors['1002'],
                'status'=>'error',
                'error'=>$result
            ])->withStatus(400);
        }

        $user = User::where('email', $req->getParam('email'))->first();
        if (empty($user)) {
             $this->logger->info('SingIn', array('MESSAGE'=>'Wrong email', 'REQUEST'=>$req->getParams()));
            return $res->withJson([
                'message' => $this->errors['1011'],
                'status' => 'error 1011',
                'error' => '403'])
                ->withStatus(403);
        }
        if (password_verify($req->getParam('password'), $user->password )OR ($req->getParam('password')==$user->password AND $this->config['devMode'] )) {
            $old_tokens = Token::where('user_id', $user->user_id)->get();
            foreach ($old_tokens as $token){
                $token->delete();
            }
            $token = $this->makeToken();
            $user->tokens()->create([
                'token' => $token,
                'expires_at'=> date("Y-m-d H:i:s", time()+(52*7*24*60*60))
            ]);
            $type = mb_strtolower(explode("\\", $user->entry_type)[2]);

            $login_data = ['message' => 'OK', 'status' => 'success', 'error' => '', 'user_id' => $user->user_id, 'email' => $user->email, 'token' => $token, $type . '_id' => $user->entry_id,
                'confirm_email' => $user->confirm_email];

            return $res->withJson($user->getEntry()->toArray() + $login_data)
                ->withStatus(200);
        } else {
            return $res->withJson([
                'message' => $this->errors['1012'],
                'status' => 'error 1012',
                'error' => '403'
            ])->withStatus(403);
        }
    }

    public function singout(Request $req, Response $res)
    {
        $id = intval($req->getParam('user_id')[0]);
//        $token = $req->getHeader('Token')[0];
//return $res->withJson(['id'=> $id],200);
//        $user = User::where('user_id', $id)->first();
        $tokens = Token::where('user_id', $id)->get();
        foreach ($tokens as $token){
            $token->delete();
        }
//
//        $login_data = ['user_id' => $user->user_id, 'token' => $token, 'type' => explode('\\', $user->entry_type)[2],
//            'status' => 'singout'];
//
//        $result = Token::deleteOne($id, $token);
        return $res->withJson(['message' => 'OK', 'status' => 'success', 'error' => '',], 200);
    }


    public function makeMe(Request $req, Response $res, $args)
    {
        $user = User::where('user_id', $args['user_id'])->first();
        $customer = Customer::where('customer_id', $user->entry_id)->first();
        $admin = new Admin();
        $admin->first_name = $customer->first_name;
        $admin->last_name = $customer->last_name;
        $admin->phone = $customer->phone;
        $admin->logo = $customer->logo;
        $admin->entry_id = $user->user_id;
        $admin->status = 1;
        $result = $admin->save();
        if ($result) {
            $customer->delete();
            $user->entry_type = 'App\Models\Admin';
            $user->save();
            $this->logger->warning('New admin', array('REQUEST'=>$req->getParams()));

            return $res->withJson(['message' => 'New admin added', 'status' => 'success', 'error' => ''], 200);
        }
        $this->logger->error('New admin error', array('message' => $this->errors['1007'], 'REQUEST'=>$req->getParams()));

        return $res->withJson([
            'message' => $this->errors['1007'],
            'status' => 'error',
            'error' => '400'
        ], 400);
    }

    public function newPassword(Request $req, Response $res)
    {
        $validation = $this->validator;
        $validation->validate($req, array(
            'password' => v::notEmpty()->length(1, 50) //TODO: turn off debug mode
        ));
        if ($validation->failed()) {
            $i=0; $result= Array();
            foreach ($validation->errors as $error){
                $result[$i]= $error;
                $i++;
            }
            $this->logger->info('Validation error', array('MESSAGE'=>$result, 'REQUEST'=>$req->getParams()));
            return $res->withJson([
                'message'=>$this->errors['1002'],
                'status'=>'error 1002',
                'error'=>$result
            ])->withStatus(400);

        }

        $id = $req->getParam('user_id');
        Token::deleteAll($id);
        User::changePassword($id, $this->config['devMode'] ? $req->getParam('password') :  password_hash($req->getParam('password'), PASSWORD_DEFAULT));

        return $res->withJson([
            'message' => $this->messages['2005'],
            'status' => 'success',
            'error' => ''
            ])->withStatus(200);
    }

    public function delUserById(Request $req, Response $res, $args)
    {

        if (isset($args['customer_id'])) {
            $entry_id = $args['customer_id'];
            $customer = Customer::where('customer_id', $entry_id)->first();
            $queue = Queue::where('customer_id', $customer->customer_id)->delete();
            $user = $customer->user;
            $customer->delete();
            $user->delete();
            return $res->withJson(['message' => 'OK', 'status' => 'success', 'error' => ''])->withStatus(200);
        } elseif (isset($args['worker_id'])) {

            $entry_id = $args['worker_id'];
            $worker = Worker::where('worker_id', $entry_id)->first();
            $queue = Queue::where('worker_id', $worker->worker_id)->get();
            foreach ($queue as $value){
                Notification::create([
                    'title' =>  $this->messages['2006'],
                    'message' => $this->messages['2007'],
                    'status' => FALSE,
                    'queue_id' => $value['queue_id'],
                    'user_id' => $value['user_id'],
                ]);
                $value->delete();
            }
            $salon = $worker->salon;
            $user = $worker->user;
            $worker->delete();
            if ($user->delete()) {
                $salon->staff_number = intval($salon->staff_number) - 1;
                $salon->save();
            }
            return $res->withJson(['message' => 'OK', 'status' => 'success', 'error' => ''])
                ->withStatus(200);
        } elseif (isset($args['salon_id'])) {
            $entry_id = $args['salon_id'];
            $salon = Salon::where('salon_id', $entry_id)->first();
            $user = $salon->user;
            $workers = $salon->workers;
            foreach ($workers as $worker) {
                $queue = Queue::where('worker_id', $worker->worker_id)->get();
                foreach ($queue as $value){
                    Notification::create([
                        'title' =>  $this->messages['2006'],
                        'message' => $this->messages['2008'],
                        'status' => FALSE,
                        'queue_id' => $value['queue_id'],
                        'user_id' => $value['user_id'],
                    ]);
                    $queue->delete();
                }
                $worker->delete();
            }
            $salon->delete();
            $user->delete();
            return $res->withJson(['message' => 'OK', 'status' => 'success', 'error' => ''])
                ->withStatus(200);

        }
        return $res->withJson(['message' => 'User type not found', 'status' => 'error', 'error' => '400'])
            ->withStatus(400);
    }

    public function delUser(Request $req, Response $res)
    {
        $validation = $this->validator;
        $validation->validate($req, [
            'email' => v::notEmpty()->email()->length(5, 255)
        ]);
        if ($validation->failed()) {
            $i=0; $result= Array();
            foreach ($validation->errors as $error){
                $result[$i]= $error;
                $i++;
            }
            // $this->logger->info('Validation error', array('MESSAGE'=>$result, 'REQUEST'=>$req->getParams()));
            return $res->withJson([
                'message'=> $this->errors['1002'],
                'status'=>'error 1002',
                'error'=>$result
            ])->withStatus(400);

        }
        $user = User::where('email', $req->getParam('email'))->first();
        $user_name = $user->email;
        $user_id = $user->user_id;

        if (isset($user)) {
            if (explode('\\', get_class($user->getEntry()))[2] == "Salon") {
                $user_type = 'Salon';
                $salon = Salon::where('salon_id', $user->entry_id)->first();
                $workers = Worker::all()->where('salon_id', 19);
                $i = 0;
                foreach ($workers as $worker) {
                    $w_array = ['Workers_' . $i => 'id: ' . $worker->worker_id . ' deleted'];
                    $i++;
                    //$worker->delete();
                }
                $salon->delete();
            } elseif (explode('\\', get_class($user->getEntry()))[2] == "Worker") {
                $user_type = 'Worker';
                $worker = Worker::where('worker_id', $user->entry_id)->first();
                $worker->delete();
            } elseif (explode('\\', get_class($user->getEntry()))[2] == "Customer") {
                $user_type = 'Customer';
                $customer = Customer::where('customer_id', $user->entry_id)->first();
                $customer->delete();
            }
        } else {
            return $res->withJson(['user' => $this->errors['1009'] ])
                ->withStatus(400);
        }
        $user->delete();
        if (isset($w_array)) {
            return $res->withJson(['user' => $user_name . ' ID ' . $user_id, 'user_type' => $user_type, 'status' => 'deleted'
                ] + $w_array)

                ->withStatus(200);
        } else {
            return $res->withJson(['user' => $user_name . ' ID ' . $user_id, 'user_type' => $user_type, 'status' => 'deleted'])

                ->withStatus(200);
        }
    }
    function recalc(Request $req, Response $res)
    {
        $salons = Salon::all();
        $i=0;
        $result=[];
        foreach ($salons as $salon){
            $workers = Worker::where('salon_id', $salon->salon_id)->count();
//            return $res->withJson(['w'=>$workers, 's'=>$salon],200);
            if ($salon->staff_number != $workers) {
                $salon->staff_number = $workers;
                $salon->save();
                $result[$i]['salon_id'] = $salon->salon_id;
                $i++;
            }
        }
        return $res->withJson(['message'=>'ok', 'error'=>false, 'ststus'=>'success', 'body'=>$result], 200);
    }

    protected function makeToken()
    {
        return sha1(random_bytes(40));
    }
}





