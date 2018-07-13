<?php
/**
 * Created by PhpStorm.
 * User: javelin
 * Date: 12.05.2017
 * Time: 15:14
 */

namespace App\Controllers;

use App\Models\Customer;
use App\Models\Notification;
use App\Models\NToken;
use App\Models\Queue;
use App\Models\Salon;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\ServiceWorker;
use App\Models\User;
use App\Models\Worker;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\DB;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;


class QueueController extends BaseController
{
    public function readTime($tt)
    {
       return \DateTime::createFromFormat("U", $tt)
            ->format("d-m-Y H:i:s");
    }

    public function freeTime(Request $req, Response $res, $args)
    {
        $result['worker'] = Worker::find($args['worker_id']);
        $result['free_time'] = $this->getFreeTime($args);
        return $res->withJson($result, 200);
    }




    public function salonServiceFreeTime(Request $req, Response $res, $args)
    {

        $salon = Salon::find($args['salon_id']);
        $workers = ServiceWorker::where('service_worker.service_id', $args['service_id'])
            ->leftJoin('services', 'service_worker.service_id', '=', 'services.service_id')
            ->leftJoin('workers', 'service_worker.worker_id', '=', 'workers.worker_id')
            ->where('workers.deleted_at', null)
            ->get();
        foreach ($workers as $id=>$worker){
            $result[$id]['user'] = $worker;
            $result[$id]['free_time'] = $this->getFreeTime([
                'worker_id'=>$worker['worker_id'],
                'date'=>$args['date']
            ]);
        }
        return $res->withJson($result, 200);

    }

    public function salonFreeTime(Request $req, Response $res, $args)
    {
        $salon = Salon::find($args['salon_id']);
//        return $res->withJson($salon, 200);
        $workers = $salon->workers->toArray();
        foreach ($workers as $id=>$worker){
            $result[$id]['worker'] = $worker;
            $result[$id]['free_time'] = $this->getFreeTime([
                'worker_id'=>$worker['worker_id'],
                'date'=>$args['date']
            ]);
        }
        return $res->withJson(['free_time'=>$result], 200);

//        return $res->withJson($workers, 200);

//        $result[0]['user_id'] = '50';
//        $result[0]['free_time'] = $this->getFreeTime(['worker_id'=>'50', 'date'=>'28-02-2018']);
//        $result[1]['user_id'] = '51';
//        $result[1]['free_time'] = $this->getFreeTime(['worker_id'=>'51', 'date'=>'28-02-2018']);
//        return $res->withJson($result, 200);
    }


    public function getFreeTime($args)
    {
        $data_week_day = \DateTime::createFromFormat("d-m-Y", $args['date'])->format("N");

        $worker = Worker::find($args['worker_id']);
        $schedules = Schedule::where('worker_id', $worker->worker_id)
            ->where('day',$data_week_day )
            ->get();
        $result =  Array();
        $j=0;$k=0;
//        return ['data'=>$data_week_day, 'worker'=>$worker, 'sched'=> $schedules, 'args'=>$args];
        $schedules_array=[];
        foreach ($schedules as $schedule){
            $unix_start_stamp = \DateTime::createFromFormat("d-m-Y H:i:s", $args['date'].' 00:00:00')
                    ->format("U") + 60*60*(intval(explode(':', $schedule->start)[0])+3);
            $unix_stop_stamp = \DateTime::createFromFormat("d-m-Y H:i:s", $args['date'].' 00:00:00')
                    ->format("U") + 60*60*(intval(explode(':', $schedule->stop)[0])+3);
//            return ['time'=> 'start: '.$unix_start_stamp.' stop: '.$unix_stop_stamp];
            $queue = Queue::where('worker_id', $worker->worker_id)
                ->leftJoin('services', 'queue.service_id', '=', 'services.service_id')
                ->where('time_stamp', '>=',$unix_start_stamp )
                ->where('time_stamp', '<=',$unix_stop_stamp)
                ->orderBy('time_stamp')
                ->get();
//            return $queue;
            $schedules_array[$k] = $schedule;
            $schedules_array['q'][$k] = $queue;
            $i=0;
            if (count($queue) == 0){
                $result[$j]['start'] = \DateTime::createFromFormat("U", $unix_start_stamp)
                    ->format("d-m-Y H:i:s");
                $result[$j]['stop'] = \DateTime::createFromFormat("U", $unix_stop_stamp)
                    ->format("d-m-Y H:i:s");
                continue;
            }
            foreach ($queue as $item){

                if ($i==0 && $unix_start_stamp < $item->time_stamp){
                    $result[$j]['start'] = $this->readTime($unix_start_stamp);
                    $result[$j]['stop'] = $this->readTime($item->time_stamp);
                    $result[$j+1]['start'] = $this->readTime($item->time_stamp + $item->duration*60);
                    $i++;$j++;
                    continue;
                }elseif ($i==0 && $unix_start_stamp = $item->time_stamp){
                    $result[$j]['start'] = $this->readTime($unix_start_stamp+ $item->duration*60);
                    $i++;$j++;
                    continue;
                }elseif ($i!=0 && !isset($result[$j-1]['stop'])){
                    $result[$j-1]['stop'] = $this->readTime($item->time_stamp);
                    $result[$j]['start'] = $this->readTime($item->time_stamp + $item->duration*60);
                    $i++;$j++;
                    continue;
                }elseif ($i!=0 && isset($result[$j]['start'])){
                    $result[$j]['stop'] = $this->readTime($item->time_stamp);
                    $result[$j+1]['start'] = $this->readTime($item->time_stamp + $item->duration*60);
                    $i++;$j++;
                    continue;
                }
            }

            if (isset($result[$j]['start']) && !isset($result[$j]['stop'])) {
                $result[$j]['stop'] = $this->readTime($unix_stop_stamp);
            }elseif (!isset($result[$j]['start'])){
                $result[$j-1]['stop'] = $this->readTime($unix_stop_stamp);
            }


            $qres[$k]=$queue;
            $k++;
            //break;
            $j++;
        }
        return $result;
    }


    public function deleteQueue(Request $req, Response $res, $args)
    {
        $user = User::where('user_id', $req->getParam('user_id'))->first();
        $queue = Queue::where('queue_id', $args['queue_id'])->first();
        $worker = Worker::find($queue->worker_id);

//        if ($user->entry_id == $queue->customer_id OR $user->entry_id == $queue->worker_id OR $user->entry_id == $worker->salon_id) {
            $id = $queue->queue_id;
            $result = $queue->delete();
            if ($result) {
                return $res->withJson([
                    'message' => $this->messages['2013'],
                    'status' => $this->messages['2011'],
                    'error' => '',
                ])->withStatus(200);
            } else {
                return $res->withJson([
                    'message' => $this->errors['1015'],
                    'status' => 'error ',
                    'error' => '',
                ])->withStatus(200);
            }
//        }
//        return $res->withJson(['message' => 'This Queue make other Customer. Check you User ID ', 'error' => '403'])
//
//            ->withStatus(403);

    }

    public function getSalonQueue(Request $req, Response $res, $args)
    {
        $is_from = $req->getParam('from');
        if (isset($is_from)) {
            $from = \DateTime::createFromFormat("d.m.Y", $is_from)->format("Y-m-d H:m:s");
        } else {
            $today = new DateTime();
            $from = $today->format("Y-m-d H:m:s");
        }
        $is_to = $req->getParam('to');
        if (isset($is_to)) {
            $to = \DateTime::createFromFormat("d.m.Y", $req->getParam('to'))->format("Y-m-d H:m:s");
        } else {
            $to = null;
        }
//         return $res->withJson(['to'=>$to,'from'=>$from])->withStatus(200);

        $customers_id = Queue::join('services', 'services.service_id', '=', 'queue.service_id')->
        where('salon_id', $args['salon_id'])->pluck('customer_id')->toArray();
        $customers_id = array_unique($customers_id);
        $i = 0;
        foreach ($customers_id as $key=>$value) {
            $customer = Customer::where('customer_id', $value)->first();
            if (!isset($customer)) continue;
//            return $res->withJson(['to'=>$customer])->withStatus(200);
            $result[$i]['customer'] = $customer->toArray();
            if (isset($to)) {
                $queue = Queue::join('services', 'services.service_id', '=', 'queue.service_id')
                ->where('queue.customer_id', $value)
                ->where('time', '>', $from)
                ->where('services.salon_id', $args['salon_id'])
                ->where('time', '<', $to)->get();
            } else {
                $queue = Queue::join('services', 'services.service_id', '=', 'queue.service_id')->
                where('queue.customer_id', $value)->where('time', '>', $from)->get();
            }

            $j = 0;
            foreach ($queue as $value1) {
                $result[$i]['customer']['queue'][$j] = $value1;
                $worker = Worker::where('worker_id', $value1['worker_id'])->first();
                $result[$i]['customer']['queue'][$j]['first_name'] = $worker->first_name;
                $result[$i]['customer']['queue'][$j]['last_name'] = $worker->last_name;
                $result[$i]['customer']['queue'][$j]['phone'] = $worker->phone;
                $j++;
            }
            $i++;
        }
        if ($result == null) {
            $result1 ['customer'] = [
                "customer_id" => null,
                "first_name" => null,
                "last_name" => null,
                "phone" => null,
                "logo" => null
            ];
            $result = [$result1];
        }
        return $res->withJson($result)

            ->withStatus(200);
    }


    public function getCustomerQueue(Request $req, Response $res, $args)
    {
        $customer = intval($args['customer_id']);
        $is_from = $req->getParam('from');
        if (isset($is_from)) {
            $from = \DateTime::createFromFormat("d.m.Y", $is_from)->format("Y-m-d H:m:s");
        } else {
            $today = new DateTime();
            $from = $today->format("Y-m-d H:m:s");
        }
        $is_to = $req->getParam('to');
        if (isset($is_to)) {
            $to = \DateTime::createFromFormat("d.m.Y", $req->getParam('to'))->format("Y-m-d H:m:s");
        } else {
            $to = null;
        }
        // return $res->withJson(['to'=>$to,'from'=>$from])->withStatus(200);
        $salons_id = Queue::join('services', 'services.service_id', '=', 'queue.service_id')->
        join('salons', 'salons.salon_id', '=', 'services.salon_id')->
        where('customer_id', $args['customer_id'])->pluck('salons.salon_id');
        $salons_id = array_unique($salons_id->toArray());
        $i = 0;
        foreach ($salons_id as $key=>$value) {
            $salon = Salon::where('salon_id', $value)->first();
            $result[$i]['salons'] = $salon->toArray();
            if (isset($to)) {
                $queue = Queue::join('services', 'services.service_id', '=', 'queue.service_id')->
                join('salons', 'salons.salon_id', '=', 'services.salon_id')->
                where('salons.salon_id', $value)->where('time', '>', $from)->
                where('time', '<', $to)->get([
                    'queue.queue_id',
                    'services.service_id',
                    'services.name',
                    'services.duration',
                    'services.price_min',
                    'services.price_max',
                    'services.logo',
                    'queue.worker_id',
                    'queue.customer_id',
                    'queue.status',
                    'queue.time',
                ]);
            } else {
                $queue = Queue::join('services', 'services.service_id', '=', 'queue.service_id')->
                join('salons', 'salons.salon_id', '=', 'services.salon_id')->
                where('salons.salon_id', $value)->where('queue.customer_id', intval($customer))->
                where('time', '>', $from)->get([
                    'queue.queue_id',
                    'services.service_id',
                    'services.name',
                    'services.duration',
                    'services.price_min',
                    'services.price_max',
                    'services.logo',
                    'queue.worker_id',
                    'queue.customer_id',
                    'queue.status',
                    'queue.time',
                ]);
            }
            $j = 0;
            foreach ($queue as $value1) {
                $result[$i]['salons']['queue'][$j]['queue_id'] = $value1['queue_id'];
                $result[$i]['salons']['queue'][$j]['service_id'] = $value1['service_id'];
                $result[$i]['salons']['queue'][$j]['worker_id'] = $value1['worker_id'];
                $worker = Worker::where('worker_id', $value1['worker_id'])->first();
                $result[$i]['salons']['queue'][$j]['first_name'] = $worker->first_name;
                $result[$i]['salons']['queue'][$j]['last_name'] = $worker->last_name;
                $result[$i]['salons']['queue'][$j]['phone'] = $worker->phone;
                $result[$i]['salons']['queue'][$j]['customer_id'] = $value1['customer_id'];
                $result[$i]['salons']['queue'][$j]['status'] = $value1['status'];
                $result[$i]['salons']['queue'][$j]['time'] = $value1['time'];
                $result[$i]['salons']['queue'][$j]['name'] = $value1['name'];
                $result[$i]['salons']['queue'][$j]['duration'] = $value1['duration'];
                $result[$i]['salons']['queue'][$j]['price_min'] = $value1['price_min'];
                $result[$i]['salons']['queue'][$j]['price_max'] = $value1['price_max'];
                $result[$i]['salons']['queue'][$j]['logo'] = $value1['logo'];
                $j++;
            }
            $i++;
        }
        if (sizeof($result) == 0) {
            //$result = Salon::where('salon_id', '')->get;
            $result1 ['salons'] = [
                "salon_id" => null,
                "first_name" => null,
                "last_name" => null,
                "business_name" => null,
                "founded_in" => null,
                "staff_number" => null,
                "rating" => null,
                "comments_number" => null,
                "phone" => null,
                "city" => null,
                "address" => null,
                "house" => null,
                "lat" => null,
                "lng" => null,
                "waze" => null,
                "logo" => null,
                "status" => null,
            ];
            $result = [$result1];
        }
        return $res->withJson($result)

            ->withStatus(200);

    }

    public function getQueue(Request $req, Response $res, $args)
    {
        $from = \DateTime::createFromFormat("d.m.Y", $req->getParam('from'))->format("Y-m-d");
        $to = \DateTime::createFromFormat("d.m.Y", $req->getParam('to'))->format("Y-m-d");

        $queue = Queue::join('services', 'services.service_id', '=', 'queue.service_id')
            ->where('worker_id', $args['worker_id'])
            ->where('time', '>', $from)
            ->where('time', '<', $to)
            ->orderBy('time')
            ->get();
        $i=0;
        foreach ($queue as $value){
            $customer = Customer::find($value->customer_id);
            $result[$i]['queue'] = $value;
            $result[$i]['customer'] = $customer;
            $i++;
        }

        if (sizeof($queue) == 0) {
            $queue[] = [
                "queue_id" => null,
                "service_id" => null,
                "worker_id" => null,
                "customer_id" => null,
                "status" => null,
                "time" => null,
                "salon_id" => null,
                "name" => null,
                "duration" => null,
                "price_min" => null,
                "price_max" => null,
                "logo" => null
            ];
        }
        return $res->withJson($result)

            ->withStatus(200);

        /*$schedules = Schedule::where('worker_id', $args['worker_id'])->select('day')->distinct()->get();
        $schedules_count = count($schedules->toArray());
        $today = new DateTime();
        //$tomorrow = new DateTime();
        //$tomorrow->modify('+1 DAY');
        //$tomorrow = $today->modify('+1 DAY');
        $week_day = date("w");
        if ($week_day == 0 ){$week_day = 7;}
        //return $res->withJson(['sf'=>$today->format("Y-m-d"), 'sdf'=>$tomorrow->format("Y-m-d")])->withStatus(200);

        for ($i=$week_day;$schedules_count;$i++){
            $result[$i]['date']= $today->format('Y-m-d H:m');
            $schedules = Schedule::where('day', $i)->orderBy('start')->get();
            //return $res->withJson($schedules)->withStatus(200);
            $queue = Queue::join('services', 'services.service_id', '=', 'queue.service_id')->
                where('worker_id', $args['worker_id'])->where('time', '>', $today->format('Y-m-d'))->
                where('time', '<', $today->modify('+1 DAY')->format('Y-m-d'))->orderBy('time')->get();
            $j=1;
            foreach ($schedules as $schedule){
                $result[$i]['day']= $i;
                $result[$i]['start']= $schedule->start;
                $result[$i]['stop']= $schedule->stop;

                $time_start = mktime(explode(':', $schedule->start)[0], explode(':', $schedule->start)[1]);
                $time_stop = mktime(explode(':', $schedule->stop)[0], explode(':', $schedule->stop)[1]);
                foreach ($queue as $value){
                    if ($time_start <= mktime($value->time) && $time_stop >= mktime($value->time)) {
                        $result[$i][$j]['queue']['start'] = data ('H:m',$value->time);
                        $result[$i][$j]['queue']['stop'] = data ('H:m', mktime($value->time));

                    }
                }

                $result[$i][$j]['start']=
                $j++;
            }
            return $res->withJson($result)->withStatus(200);
        }
        //Sample::select('link')->distinct()->count();
        return $res->withJson($schedules)->withStatus(200);


        //return $res->withJson(['sgf'=>$week_day])->withStatus(201);

        return $res->withJson($schedules)->withStatus(200);*/


    }

    public function confirmQueue(Request $req, Response $res, $args)
    {
        $queue = Queue::where('queue_id', $args['queue_id'])->first();
        $queue->status = 2;
        $queue->save();
        $customer = Customer::where('customer_id', $queue->customer_id)->first();
        $email = User::where('entry_id', $queue->customer_id)->
        where('entry_type', 'App\Models\Customer')->pluck('email')->first();
        $mail = new EmailController();
        $user_name = $customer->last_name . " " . $customer->first_name;
        $mail->AddAddress($email, $user_name); // Получатель
//        $mail->Subject = htmlspecialchars('You have new queue!');  // Тема письма
        $letter = file_get_contents(__DIR__.'/../letters/confirm_queue.html');
        $title = explode('title', $letter)[1];
        $title = substr($title, 1, strlen($title)-3);
        $mail->Subject = htmlspecialchars($title);  // Тема письма
        if ($letter) {
            $letter_body = sprintf($letter, $user_name);
            $mail->MsgHTML($letter_body); // Текст сообщения
            $mail->AltBody = "Dear " . $user_name . ", You have new queue!";
            $result = $mail->Send();
            return $res->withJson(['message'=>'OK', 'success'=>'', 'error'=>false])
                ->withStatus(200);
        }
        return $res->withJson(['message'=>'cant send email', 'success'=>'warning', 'error'=>false])
            ->withStatus(200);
    }


    public function addQueue(Request $req, Response $res, $args)
    {
//        return $res->withJson(['message'=>'ff', '$time_stamp'=>'$time_stamp', 'error'=> true ],200);

        //$week_day = strftime("%u", strtotime($req->getParam('time')));
        //$customer_id = $req->getParam('customer_id');
        $Queue = Queue::where('worker_id', $args['worker_id'])->get();
        $time_stamp = (new DateTime($req->getParam('time')))->format("U")+3*60*60;
//        return  $res->withJson(['message'=>$Queue, '$time_stamp'=>$time_stamp, 'error'=> $args['worker_id'] ],200);
        foreach ($Queue as $item){
            $service = Service::find($args['service_id']);

            if ($item->time_stamp <= $time_stamp && $item->time_stamp+($service->duration*60)>$time_stamp  ){
                $error_msg= 'This time is busy';
               return $res->withJson(['message'=>$error_msg, 'status'=>'error', 'error'=> true ],200);
            }
        }

        $time = date('Y-m-d H:i:s', strtotime($req->getParam('time')));
        $now= date("U", time());

        if ($time_stamp < $now + 60*60*24* $this->config['queue_days']) {
            $queue = Queue::create([
                    'time' => $time,
                    'customer_id' => $req->getParam('customer_id'),
                    'time_stamp'=> $time_stamp,
                    'status'=> 2,
                ] + $args);
            return $res->withJson($queue->toArray() + ['message'=>'OK', 'status'=>'success', 'error'=> false],200);
        }
        $queue = Queue::create([
            'time' => $time,
            'customer_id' => $req->getParam('customer_id'),
            'time_stamp'=> $time_stamp,
            ] + $args);
        $queue = Queue::where('queue_id', $queue->queue_id)->first();
        $message = array('message' => 'Dear worker you are have new queue!');
        $message = 'Dear worker you are have new queue: '.$time;
        $user = User::where('entry_type', 'App\Models\Worker')->where('entry_id', $args['worker_id'])->first();
        $worker = Worker::where('worker_id', $args['worker_id'])->first();
        //$customer = Customer::where('customer_id',$req->getParam('customer_id'))->first();
//        $ntoken = NToken::where('user_id', $user->user_id)->pluck('n_token');
        $notification = Notification::create(
            [
                'title' =>  $this->messages['2014'],
                'message' => $message,
                'status' => FALSE,
                'queue_id' => $queue->queue_id,
                'user_id' => $user->user_id,
            ]);

//        $result = $notification->send_notifications($ntoken, $message);
//        $notification->queue_id = $queue->queue_id;
//        $notification->message = $message;
//        $notification->save();
        $email = $user->email;

        $mail = new EmailController();
        $user_name = $worker->last_name . " " . $worker->first_name;
        $mail->AddAddress($email, $user_name); // Получатель
//        $mail->Subject = htmlspecialchars('You have new queue!');  // Тема письма
        $letter = file_get_contents(__DIR__ . '/../letters/worker_new_queue.html');
        $title = explode('title', $letter)[1];
        $title = substr($title, 1, strlen($title)-3);
        $mail->Subject = htmlspecialchars($title);  // Тема письма
        if ($letter) {
            $letter_body = sprintf($letter, $user_name);
            $mail->MsgHTML($letter_body); // Текст сообщения
            $mail->AltBody = "Dear " . $user_name . ", You have new queue!";
            $result = $mail->Send();
            return $res->withJson($queue->toArray()+['message'=>'ok', 'status'=>'success', 'error'=>false ])
                ->withStatus(200);
        }
        return $res->withJson(['message'=>'cant send email', 'status'=>'warning', 'error'=>false],201);
    }
}