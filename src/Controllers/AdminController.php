<?php
//session_start();

/**
 * Created by PhpStorm.
 * User: javelin
 * Date: 16.05.2017
 * Time: 12:38
 */

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Answer;
use App\Models\Comment;
use App\Models\Customer;
use App\Models\Message;
use App\Models\Salon;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\ServiceWorker;
use App\Models\User;
use App\Models\Worker;
use mysqli_sql_exception;
use Slim\Http\Request;
use Slim\Http\Response;
use duncan3dc\Laravel\BladeInstance;


class AdminController extends BaseController
{
    protected $blade;

    public function __construct()
    {
        session_start();
      $this->blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");

    }

  public function check(Request $req, Response $res)
    {
        $user = User::where('login', $req->getParam('login'))->first();
        if (isset($user)) {

        }

    }

    public function emptyPage()
    {
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        if (isset($_SESSION['user_id'])) {
            $admin = Admin::where('admin_id', $_SESSION['user_id'])->first();
            echo $blade->render("empty", [
                'admin' => $admin,
            ]);

        }

    }

    public function newSalon(Request $req, Response $res)
    {
        session_start();
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        if (isset($_SESSION['user_id'])) {
            $admin = Admin::where('admin_id', $_SESSION['user_id'])->first();

            if (isset($admin)) {
                echo $blade->render("new_salon", [
                    'admin' => $admin,
                    'menu' => 'salons',
                ]);
                return;
            }
        }
        echo $blade->render("login");
        return;
    }

    private function sendEmail($params)
    {
        $mail = new EmailController();

        $user_name = $params['name'];
        $mail->AddAddress($params['email'], $user_name); // Получатель
        $mail->Subject = htmlspecialchars($params['subj']);  // Тема письма
        $mail->MsgHTML($params['letter_body']); // Текст сообщения
        $mail->AltBody = "Dear " . $user_name . ", " . $params['subj'];
        return $mail->Send();
    }

    public function singin(Request $req, Response $res)
    {
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        echo $blade->render("singin");
        return;
    }

    public function workWithMessage(Request $req, Response $res, $args)
    {
        session_start();
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        if (isset($_SESSION['user_id'])) {
            $admin = Admin::where('admin_id', $_SESSION['user_id'])->first();
            if (isset($admin)) {
                if ($req->getParam('operator') == 'Delete') {
                    $message = Message::where('message_id', $args['message_id'])->first();
                    $message->deleted_at = date('Y-m-d H:i:s');
                    $message->save();
                    $allmessages = Message::where('deleted_at', null)->orderBy('created_at', 'desc')->get();
                    echo $blade->render("messages", [
                        'admin' => $admin,
                        'allmessages' => $allmessages,
                    ]);
                } elseif ($req->getParam('operator') == 'Answer') {
                    $message = Message::where('message_id', $args['message_id'])->first();
                    // $messages = Message::where('answer_at', '=', null)->get();
                    $answers = $message->answers;
                    echo $blade->render("answer", [
                        'admin' => $admin,
                        'message' => $message,
                        'answers' => $answers,
                        'menu' => 'messages'
                    ]);
                } elseif ($req->getParam('operator') == 'Send') {

                    $answer = new Answer();
                    $answer->message_id = $args['message_id'];
                    $answer->text = $req->getParam('textanswer');
                    $answer->save();
                    $message = Message::where('message_id', $args['message_id'])->first();
                    $message->answer_at = date('Y-m-d H:i:s');
                    $message->save();
                    $params = [
                        'name' => $message->name,
                        'email' => $message->user->email,
                        'text' => $answer->text,
                        'subj' => 'New mail from HairTaime Admin',
                        'letter_body' => '
<head>
<title>New answer on you message</title>
</head>
<body>
<br>
' . 'text' . '
<br><br>
You can contact us on <a href="mailto:admin@hairtime.co.il">admin@hairtime.co.il</a></p><br>

<p>With best regards, <br /><br>

The HairTime Team.</p>',
                    ];
                    $result = $this->sendEmail($params);

                    //return $res->withStatus(200)->withJson($result);

                    header('Location: ' . 'https://hairtime.co.il/admin/message/' . $message->message_id . '?operator=Answer');

                } elseif ($req->getParam('operator') == 'Cancel') {
                    header('Location: ' . 'https://hairtime.co.il/admin/message');
                }

                return;
            }
        }
        echo $blade->render("login");
        return;

    }

    public function getAllMessage(Request $req, Response $res)
    {
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");

        if (isset($_SESSION['user_id'])) {
            if ($admin = Admin::where('entry_id', $_SESSION['user_id'])->count() > 0) {
                if ($req->getParam('search') != null) {
                    $allmessages = Message::where('deleted_at', null)->where('name', 'LIKE', '%' . $req->getParam('search') . '%')->orderBy('created_at', 'desc')->get();

                } else {
                    $allmessages = Message::where('deleted_at', null)->orderBy('created_at', 'desc')->get();
                }
                $admin = Admin::where('entry_id', $_SESSION['user_id'])->first();
                echo $blade->render("messages", [
                    'admin' => $admin,
                    'allmessages' => $allmessages,
                    'menu' => 'messages'
                ]);
                return;
            }
        }
        echo $blade->render("login");
        return;
    }

    public function getNewMessage(Request $req, Response $res)
    {
        $newMessages = Message::where('deleted_at', null)->where('answer_at', '=', null)
            ->orderBy('created_at', 'desc')->get();

        return $res->withJson($newMessages)->withStatus(200);
    }

    public function newPaddssword(Request $req, Response $res, $args)
    {
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");

        if (isset($_SESSION['user_id'])) {
            if ($admin = Admin::where('entry_id', $_SESSION['user_id'])->count() > 0) {
                $user = User::where('user_id', $args['user_id'])->first();
                $entry_type = explode("\\", $user->entry_type)[2];
                $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
                $max = 8;
                $password = null;
                while ($max--)
                    $password .= $chars[rand(0, 61)];
                $user->password = $password;
                $user->save();

                if ($entry_type == 'Customer') {
                    $customer = $user->customer; //Customer::where('customer_id', $user->entry_id);
                    $user_name = $customer->last_name . " " . $customer->first_name;
                } elseif ($entry_type == 'Salon') {
                    $salon = $user->salon; //Customer::where('customer_id', $user->entry_id);
                    $user_name = $salon->last_name . " " . $salon->first_name;

                } elseif ($entry_type == 'Worker') {
                    $worker = $user->worker; //Customer::where('customer_id', $user->entry_id);
                    $user_name = $worker->last_name . " " . $worker->first_name;

                } elseif ($entry_type == 'Admin') {
                    $admin = Admin::where('admin_id', $user->entry_id)->first();
                    $user_name = $admin->last_name . " " . $admin->first_name;
                }
                $mail = new EmailController();
                $mail->AddAddress($user->email, 'Vasya Pupkin'); // Получатель

                $mail->Subject = htmlspecialchars('New password for HairTime application');  // Тема письма
                //return $res->withJson($mail, 200);

                $letter_body = '
<head>
<title>New password for HairTime application</title>
</head>
<body>
<img alt="HairTime" src="https://hairtime.co.il/img/image.jpg" style="float: right; align-items:right; width: 400px; height: 107px;" />
<br>
<h1>&nbsp;</h1>

<h1>&nbsp;</h1>

<h2>Dear {$user_name},</h2>
<p>temporary password for your account in HairTime application is: <b>{$password}</b></p>
Please, login in your application and change this temporary password!
<br><br>
If you have any issues confirming your email we will be happy to help you. You can contact us on 
<a href="mailto:admin@hairtime.co.il">admin@hairtime.co.il</a></p><br>

<p>With best regards, <br /><br>

The HairTime Team.</p>';

                $mail->MsgHTML($letter_body); // Текст сообщения
                $mail->AltBody = "Dear " . $user_name . ", temporary password for your account in HairTime application is: " . $password;
                $result = $mail->Send();
                if ($result) {
                    return $res->withJson(['message' => 'OK', 'status' => 'success', 'error' => ''])

                        ->withStatus(200);
                } else {
                    return $res->withJson(['message' => 'Error, something wrong', 'status' => 'error', 'error' => '400'])

                        ->withStatus(400);
                }
            }
        } else {
            echo $blade->render("login");
            return;
        }

        echo $blade->render("login");
        return;
    }

    public function messageToAdmin(Request $req, Response $res)
    {

        $user = User::where('email', $req->getParam('email'))->first();

        if ($user != null) {
            $message = new Message();
            $message->user_id = $user->user_id;
            $message->name = $req->getParam('name');
            $message->message = $req->getParam('message');
            $message->save();
            return $res->withJson(['message' => 'OK', 'status' => 'success', 'error' => ''], 200);

        } else {
            return $res->withJson(['message' => 'Bad email', 'status' => 'error', 'error' => '400 Bad request'], 400);
        }

    }

    public function messageToUser(Request $req, Response $res)
    {
        $user = User::where('email', $req->getParam('email'))->first();
        if ($user != null) {
            $textMsg = $req->getParam('text');
            $mail = new EmailController();
            $entry_type = explode("\\", $user->entry_type)[2];
            $user_name = '';
            if ($entry_type == 'Customer') {
                $customer = $user->customer; //Customer::where('customer_id', $user->entry_id);
                $user_name = $customer->last_name . " " . $customer->first_name;
            } elseif ($entry_type == 'Salon') {
                $salon = $user->salon; //Customer::where('customer_id', $user->entry_id);
                $user_name = $salon->last_name . " " . $salon->first_name;

            } elseif ($entry_type == 'Worker') {
                $worker = $user->worker; //Customer::where('customer_id', $user->entry_id);
                $user_name = $worker->last_name . " " . $worker->first_name;

            } elseif ($entry_type == 'Admin') {
                $admin = Admin::where('admin_id', $user->entry_id)->first();
                $user_name = $admin->last_name . " " . $admin->first_name;
            }
            $mail->AddAddress($user->email, $user_name); // Получатель
            $mail->Subject = htmlspecialchars('New message from Hairtime admin');  // Тема письма
            $letter_body = '
<head>
<title>You are have new message from HairTime application admin</title>
</head>
<body>
<img alt="HairTime" src="https://hairtime.co.il/img/image.jpg" style="float: right; align-items:right; width: 400px; height: 107px;" />
<br>
<h1>&nbsp;</h1>

<h1>&nbsp;</h1>

<h2>Dear ' . $user_name . ',</h2>
<p>You are heve new message from HairTime application admin</p>
<br>
' . $textMsg . '
<br>
<br>
If you have any questions, we will be happy to help you. You can contact us on 
<a href="mailto:admin@hairtime.co.il">admin@hairtime.co.il</a></p><br>

<p>With best regards, <br /><br>

The HairTime Team.</p>';

            $mail->MsgHTML($letter_body); // Текст сообщения
            $mail->AltBody = "Dear " . $user_name . ", new email from HairTaime admin";
            //$result = $mail->Send();
            if (!$mail->send()) {
                return $res->withJson(['message' => $mail->ErrorInfo, 'status' => 'error', 'error' => ''])->withStatus(200);
            } else {
                return $res->withJson(['message' => 'OK', 'status' => 'sucess', 'error' => ''])->withStatus(200);
            }
        } else {
            return $res->withJson(['message' => "User with this e-mail does't found.", 'error' => "404"])->withStatus(404);
        }

    }

    public function newPassword(Request $req, Response $res)
    {
        $user = User::where('email', $req->getParam('email'))->first();
        if ($user != null) {
            $textMsg = $req->getParam('text');
            $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
            $max = 8;
            $password = null;
            while ($max--)
                $password .= $chars[rand(0, 61)];
            $user->password = $password;
            $user->save();

            $mail = new EmailController();
            $entry_type = explode("\\", $user->entry_type)[2];
            $user_name = '';
            if ($entry_type == 'Customer') {
                $customer = $user->customer; //Customer::where('customer_id', $user->entry_id);
                $user_name = $customer->last_name . " " . $customer->first_name;
            } elseif ($entry_type == 'Salon') {
                $salon = $user->salon; //Customer::where('customer_id', $user->entry_id);
                $user_name = $salon->last_name . " " . $salon->first_name;

            } elseif ($entry_type == 'Worker') {
                $worker = $user->worker; //Customer::where('customer_id', $user->entry_id);
                $user_name = $worker->last_name . " " . $worker->first_name;

            } elseif ($entry_type == 'Admin') {
                $admin = Admin::where('admin_id', $user->entry_id)->first();
                $user_name = $admin->last_name . " " . $admin->first_name;
            }
            $mail->AddAddress($user->email, $user_name); // Получатель
            $mail->Subject = htmlspecialchars('New password for HairTime application');  // Тема письма
            $letter_body = '
<head>
<title>New password for HairTime application</title>
</head>
<body>
<img alt="HairTime" src="https://hairtime.co.il/img/image.jpg" style="float: right; align-items:right; width: 400px; height: 107px;" />
<br>
<h1>&nbsp;</h1>

<h1>&nbsp;</h1>

<h2>Dear ' . $user_name . ',</h2>
<p>temporary password for your account in HairTime application is: <b>' . $password . '</b></p>
Please, login in your application and change this temporary password!
<br><br>
Dont show it to anybody!
<br>
If you have any questions, we will be happy to help you. You can contact us on 
<a href="mailto:admin@hairtime.co.il">admin@hairtime.co.il</a></p><br>

<p>With best regards, <br /><br>

The HairTime Team.</p>';

            $mail->MsgHTML($letter_body); // Текст сообщения
            $mail->AltBody = "Dear " . $user_name . ", new password for HairTaime app: " . $password;
            //$result = $mail->Send();
            if (!$mail->send()) {
                return $res->withJson(['message' => $mail->ErrorInfo, 'status' => 'error', 'error' => ''])->withStatus(200);
            } else {
                return $res->withJson(['message' => 'OK', 'status' => 'sucess', 'error' => ''])->withStatus(200);
            }
        } else {
            return $res->withJson(['message' => "User with this e-mail does't found.", 'error' => "404"])->withStatus(404);
        }

    }

    public function comments(Request $req, Response $res, $args)
    {
        session_start();
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        if (isset($_SESSION['user_id'])) {
            if ($admin = Admin::where('entry_id', $_SESSION['user_id'])->count() > 0) {
                $admin = Admin::where('entry_id', $_SESSION['user_id'])->first();
                if ($req->getMethod() === 'POST') {
                    if ($req->getParam('search') != null) {
                        if (intval($req->getParam('search')) == 0) {
                            $result = array();
                            $salons = Salon::where('business_name', 'like', $req->getParam('search'))->get();
                            foreach ($salons as $salon) {
                                $comments = Comment::where('salon_id', $salon['salon_id'])->where('del', false)->orderBy('created_at')->get();
                                $result = $result + $comments->toArray();
                            }
                        } else {
                            $result = Comment::where('comment_id', $req->getParam('search'))->first();
                        }
                        echo $blade->render("comments", ['comments' => $result, 'menu' => 'comments', 'admin' => $admin, 'vis' => 'visible']);
                    }
                    if ($req->getParam('operator') == 'Delete') {
                        //return $res->withJson($req->getParams())->withStatus(200);
                        $comment = Comment::where('comment_id', $args['comment_id'])->first();
                        $comment->del = true;
                        $comment->save();
                    }
                    if ($req->getParam('operator') == 'Edit') {
                        //return $res->withJson($req->getParams())->withStatus(200);
                        $comment = Comment::where('comment_id', $args['comment_id'])->first();
                        $comment->body = $req->getParam('comment');
                        $comment->save();

                    }
                    //return $res->withJson($req->getParams())->withStatus(200);
                }
                $comments = Comment::orderBy('created_at', 'desc')->where('del', false)->take(10)->get();
                echo $blade->render("comments", [
                    'comments' => $comments,
                    'menu' => 'comments',
                    'admin' => $admin,
                    'vis' => 'visible',
                ]);
                return;
            }

        } else {
            echo $blade->render("login");
            return;
        }
    }

    public function profile(Request $req, Response $res, $args)
    {
        session_start();
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        if (isset($_SESSION['user_id'])) {
            if (isset($args['admin_id'])) {
                if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                    $admin = Admin::where('admin_id', $args['admin_id'])->first();
                    $user = $admin->user;
                    echo $blade->render("profile", [
                        'edit' => true,
                        'admin' => $admin,
                        'user' => $user,
                        'menu' => 'profile',
                    ]);
                    return;
                } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $admin = Admin::where('admin_id', $args['admin_id'])->first();
                    $user = $admin->user;
                    $admin->first_name = $req->getParam('first_name');
                    $admin->last_name = $req->getParam('last_name');
                    $admin->save();
                    echo $blade->render("profile", [
                        'edit' => true,
                        'admin' => $admin,
                        'user' => $user,
                        'menu' => 'profile',
                    ]);
                    return;

                }

            } else {
                $admin = Admin::where('entry_id', $_SESSION['user_id'])->first();
                $user = User::where('user_id', $_SESSION['user_id'])->first();
                if ($admin->status == 1) {
                    $admins = Admin::get();
                    echo $blade->render("profile", [
                        'edit' => true,
                        'admin' => $admin,
                        'user' => $user,
                        'admins' => $admins,
                        'menu' => 'profile',
                    ]);
                } else {
                    echo $blade->render("profile", [
                        'edit' => true,
                        'admin' => $admin,
                        'user' => $user,
                        'menu' => 'profile',
                    ]);

                }
                return;
            }
        }
            echo $blade->render("login");
            return;
    }


    /** Customer
     *
     *
     */
    public function customerList(Request $req, Response $res)
    {
        session_start();
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        if (isset($_SESSION['user_id'])) {
            $admin = Admin::where('entry_id', $_SESSION['user_id'])->first()->toArray();
            if (isset($admin)) {
                $admin['token'] = $_SESSION['token'];
                $customers = Customer::orderBy('customer_id')->get();
                echo $blade->render('customers_list', [
                    'admin' => $admin,
                    'menu' => 'customers',
                    'customers' => $customers,
                ]);
            }
        } else {
            echo $blade->render("login");
            return;
        }

    }

    public function setCustomer(Request $req, Response $res, $args)
    {
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        if (isset($_SESSION['user_id'])) {
            $admin = Admin::where('entry_id', $_SESSION['user_id'])->first();
            if (isset($admin)) {

                $customer = Customer::where('customer_id', $args['customer_id'])->first();
                //return $res->withStatus(200)->withJson($customer);

                $user = $customer->user;
                $customer->first_name = $req->getParam('first_name');
                $customer->last_name = $req->getParam('last_name');
                $customer->phone = $req->getParam('phone');

                $customer->save();


                echo $blade->render("edit_customer", [
                    'admin' => $admin,
                    'customer' => $customer,
                    'user' => $user,
                    'menu' => 'customer',
                ]);
                return;
            }
        }
        echo $blade->render("login");
        return;

    }

    public function getCustomer(Request $req, Response $res, $args)
    {
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        if (isset($_SESSION['user_id'])) {
            $admin = Admin::where('entry_id', $_SESSION['user_id'])->first();
//            return $res->withStatus(200)->withJson($admin);
            if (isset($admin)) {
                $admin['token'] = $_SESSION['token'];
                $customer = Customer::where('customer_id', $args['customer_id'])->first();
                $user = $customer->user;
                echo $blade->render("edit_customer", [
                    'admin' => $admin,
                    'customer' => $customer,
                    'user' => $user,
                    'menu' => 'customer',
                ]);
                return;
            }
        }
        echo $blade->render("login");
        return;
    }


    /** login  logout makeToken
     *
     *
     */
    public function logout()
    {
        session_start();
        $_SESSION['user_id'] = null;
        $_SESSION['token'] = null;

        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        echo $blade->render("login");
        return;

    }

    protected function makeToken()
    {
        return sha1(random_bytes(40));
    }

    public function login(Request $req, Response $res)
    {
//        return $res->withJson(['error'=>'we are here'], 200);

        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try { 
                $admin = User::where('email', $req->getParam('email'))->first();
                if ($admin->password == $req->getParam('password')) {
                    $token = $this->makeToken();
                    $admin->tokens()->create([
                        'token' => $token,
                        'expires_at'=> date("Y-m-d H:i:s", time()+(7*24*60*60))
                    ]);
                    session_start();
                    $_SESSION['user_id'] = $admin->user_id;
                    $_SESSION['token'] = $token;
                    header('Location:' . $_SERVER['HTTP_REFERER']);
                } else {
                    echo $blade->render("login", ['error' => 'User name or login incorrect!']);
                    return;
                }     
            } catch (mysqli_sql_exception $e) {
                return $res->withJson(['error'=> $e->getMessage()], 500);
            }
        }
        if (isset($_SESSION['user_id']) && isset($_SESSION['token'])) {
            header('Location: https://hairtime.co.il/admin');
        } else {
            echo $blade->render("login");
            return;
        }
    }


    /** Worker
     *get Worker list
     *
     */
    public function workerList(Request $req, Response $res)
    {
        session_start();
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");

        if (isset($_SESSION['user_id'])) {
            $admin = Admin::where('entry_id', $_SESSION['user_id'])->first();
            if (isset($admin)) {
            }
            $admin['token'] = $_SESSION['token'];
            $workers = Worker::get()->toArray();
            echo $blade->render("workers_list", [
                'admin' => $admin,
                'workers' => $workers,
                'menu' => 'workers',
            ]);
            return;
        } else {
            echo $blade->render("login");
            return;
        }
    }

    /**
     * save Worker
     * @param Request $req
     * @param Response $res
     * @param $args
     */
    public function saveWorker(Request $req, Response $res, $args)
    {
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");

        if (isset($_SESSION['user_id'])) {

            $worker = Worker::where('worker_id', $args['worker_id'])->first();
            $user = $worker->user;
            //return $res->withJson(['dsfsd'=>$user, 'wwww'=>$worker],200);
            if ($req->getParam('email') != $user->email) {
                $user->email = $req->getParam('email');
                $user->confirm_email = 0;
                $user->save();
            }
            $worker->first_name = $req->getParam('first_name');
            $worker->last_name = $req->getParam('last_name');
            $worker->specialization = $req->getParam('specialization');
            $worker->start_year = $req->getParam('start_year');
            $worker->phone = $req->getParam('phone');
            $admin = Admin::where('entry_id', $_SESSION['user_id'])->first();

            if ($worker->save()) {
                header('Location: https://hairtime.co.il/admin/worker/' . $worker->worker_id);
            } else {
                return $res->withJson(["message'=>'Worker doesn\'t created", 'status' => 'error', 'error' => 'Not created something wrong'], 400);
            }

        } else {
            return $res->withJson(['message' => 'Please use your email and pass for login.', 'status' => 'error', 'error' => '401 Unauthorized '], 401);
        }
        echo $blade->render("login");
        return;
    }

    /**
     * get Worker by ID
     * @param Request $req
     * @param Response $res
     * @param $args
     */
    public function getWorker(Request $req, Response $res, $args)
    {
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        if (isset($_SESSION['user_id'])) {
            $admin = Admin::where('entry_id', $_SESSION['user_id'])->first();
//            return $res->withStatus(200)->withJson($admin);
            if (isset($admin)) {
                $admin['token'] = $_SESSION['token'];
                $sw = ServiceWorker::where('worker_id', $args['worker_id'])->get();
                $i = 0;
                foreach ($sw as $value) {
                    $result[$i] = Service::where('service_id', $value['service_id'])->first();
                    $result[$i]['worker'] = Worker::where('worker_id', $args['worker_id'])->first();
                    $result[$i]['worker']['description'] = $value['description'];
                    $result[$i]['worker']['service_logo'] = $value['logo'];
                    $i++;
                }
                $worker = Worker::where('worker_id', $args['worker_id'])->first();
                $services = $worker->services;
                $salon_services = Service::where('salon_id', $worker->salon_id)->get();
                $schedules = $schedules = Schedule::where('worker_id', $args['worker_id'])
                    ->orderBy('day')
                    ->orderBy('start')
                    ->get()
                    ->toArray();

                $user = $worker->user;
                echo $blade->render("edit_worker", [
                    'admin' => $admin,
                    'worker' => $worker,
                    'user' => $user,
                    'services' => $result,
                    'salon_services' => $salon_services,
                    'schedules' => $schedules,
                    'menu' => 'workers',

                ]);
                return;
            }
        }
        echo $blade->render("login");
        return;
    }

    /** Salon
     *
     * get salons list
     */
    public function salons(Request $req, Response $res)
    {

        session_start();
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");

        if (isset($_SESSION['user_id'])) {

            $admin = Admin::where('entry_id', $_SESSION['user_id'])->first();

            $salons = null;
            if ($req->getMethod() == 'POST') {
                if ($req->getParam('search') != null) {
                    if (intval($req->getParam('search')) == 0) {
                        $salons = Salon::where('business_name', 'like', "%" . $req->getParam('search') . "%")->paginate(5);
                    } else {
                        $salons = Salon::where('salon_id', $req->getParam('search'))->paginate(5);

                    }
                }
            } else {
                $salons = Salon::all();
            }
            $method = $req->getMethod();
            $admin = $admin->toArray();
            $admin['token'] = $_SESSION['token'];
            echo $blade->render("index", [
                'admin' => $admin,
                'method' => $method,
                'salons' => $salons->toArray(),
                'req' => $req->getParams(),
                'menu' => 'salons',
            ]);
            return;
        } else {

            echo $blade->render("login");
            return;
        }
    }

    /** get Salon by ID
     * @param Request $req
     * @param Response $res
     * @param $args
     */
    public function getSalon(Request $req, Response $res, $args)
    {
        //session_start();
        //return $res->withJson(['rty'=>'fff'],201);
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        if (isset($_SESSION['user_id'])) {
            $admin = Admin::where('entry_id', $_SESSION['user_id'])->first()->toArray();
            //unset($admin['entry_id']);
            unset($admin['status']);
            $admin['token'] = $_SESSION['token'];
            $salon = Salon::where('salon_id', $args['salon_id'])->first();
            $user = $salon->user;
            $workers = $salon->workers;
            $services = Service::where('salon_id', $salon->salon_id)->get();
            $comments = $salon->comments;
            $result = array();
            foreach ($comments as $comment) {
                $customerAuthor = $comment->customer;
                $result[] = $comment;
            }

            echo $blade->render("edit_salon", [
                'admin' => $admin,
                'salon' => $salon,
                'req' => $req->getParams(),
                'menu' => 'salons',
                'workers' => $workers,
                'services' => $services,
                'comments' => $result,
                'user' => $user,
            ]);
            return;
        } else {
            echo $blade->render("login");
            return;
        }

    }

    /**  edit salon
     * @param Request $req
     * @param Response $res
     * @param $args
     */

    public function editSalon(Request $req, Response $res, $args)
    {

        //return $res->withJson(['rr'=>'tt'])->withStatus(403);
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        if (isset($_SESSION['user_id'])) {
            $admin = Admin::where('entry_id', $_SESSION['user_id'])->first()->toArray();
            if (isset($admin)) {
                if ($req->getParam('status') == 'Active' || $req->getParam('status') == 'Inactive') {
                    $salon = Salon::find($args['salon_id']);
                    $salon->status = $req->getParam('status');
                    $salon->save();
                    //return []
                } else {
                    $params = $req->getParams();
                    //return $res->withJson($params,200);
                    unset($admin['status']);
                    $admin['token'] = $_SESSION['token'];
                    $method = $req->getMethod();
                    $salon = Salon::where('salon_id', $args['salon_id'])->first();
                    $salon->first_name = $params["first_name"];
                    $salon->last_name = $params["last_name"];
                    $salon->business_name = $params["business_name"];
                    $salon->founded_in = $params["founded_in"];
                    $salon->city = $params["city"];
                    $salon->address = $params["address"];
                    $salon->house = $params["house"];
                    $salon->lat = $params["lat"];
                    $salon->lng = $params["lng"];
                    $salon->phone = $params["phone"];
                    $salon->waze = $params["waze"];
                    $salon_res = $salon->save();
                    $salon = Salon::where('salon_id', $args['salon_id'])->first();
                    $user = $salon->user;
                    if (trim($user->email) != trim($params['email'])) {
                        $user->email = $params['email'];
                        $user->confirm_email = "0";
                    }
                    $user_res = $user->save();
                    if ($salon_res && $user_res) {
                        return $res->withJson(['message' => 'OK', 'status' => 'success', 'error' => ''], 200);
                    } elseif ($salon_res) {
                        return $res->withJson(['message' => 'User data not saved', 'status' => 'error', 'error' => '409 Conflict'], 200);
                    } elseif ($user_res) {
                        return $res->withJson(['message' => 'Salon data not saved', 'status' => 'error', 'error' => '409 Conflict'], 200);
                    }
                }
                $salons = Salon::all();
                echo $blade->render("index", [
                    'admin' => $admin,
                    'method' => $method,
                    'salons' => $salons,
                    'req' => $req->getParams(),
                    'menu' => 'salons',
                ]);
                return;
            }
        } else {
            echo $blade->render("login");
            return;
        }
    }


    /***** Services
     *
     *Get service's list
     *return Service
     */
    public function servicesList(Request $req, Response $res)
    {
        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        if (isset($_SESSION['user_id'])) {
            $admin = Admin::where('entry_id', $_SESSION['user_id'])->first();
            $services = Service::all();
            $admin = $admin->toArray();
            $admin['token'] = $_SESSION['token'];
            echo $blade->render("services_list", [
                'admin' => $admin,
                'services' => $services->toArray(),
                'req' => $req->getParams(),
                'menu' => 'services',
            ]);
            return;
        } else {
            echo $blade->render("login");
            return;
        }
    }

    public function getServices(Request $req, Response $res, $args){
        if (isset($_SESSION['user_id'])) {
            $admin = Admin::where('entry_id', $_SESSION['user_id'])->first();
            if (isset($admin)){
              $service = Service::find($args['service_id']);
              $salon = Salon::find($service->salon_id);
              $admin = $admin->toArray();
              $admin['token'] = $_SESSION['token'];
              echo $this->blade->render("edit_service", [
                'admin' => $admin,
                'service' => $service->toArray(),
                'salon' => $salon->toArray(),
                'menu' => 'services',
              ]);
              return;
            }else{
              echo $this->blade->render("login");
              return;
            }
        } else {
    echo $this->blade->render("login");
    return;
}

    }

    public function index($user)
    {

        $blade = new BladeInstance(__DIR__ . "/../../public/Views", __DIR__ . "/../../public/Views/cache");
        $result = $blade->exists('index');
        echo $blade->render("index", $user);

        echo $blade->render("index", ['url' => 'http://hairtime.co.il/uploads/img-20170325-58d66c0f72c26', 'name' => 'Vitaliy ZALYOTIN']);
        //return $res->withBody($blade->render("index"))->withStatus(200);


    }

}