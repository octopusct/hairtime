<?php
/**
 * Created by PhpStorm.
 * User: Javelin
 * Date: 30.11.2017
 * Time: 14:19
 */

namespace App\Controllers;


use App\Models\Admin;
use App\Models\Customer;
use App\Models\Salon;
use App\Models\Service;
use App\Models\User;
use App\Models\Worker;
use Slim\Http\Request;
use Slim\Http\Response;

class AjaxController extends BaseController

{
  /**
   * delete service by ID
   * @param Request $req
   * @param Response $res
   * @param $args
   * @return static
   */
    public function delService(Request $req, Response $res, $args)
    {
        $servce = Service::find($args['service_id']);
        $servce->delete();
        return $res->withJson(['message' => 'Service successfully deleted!', 'status' => 'success', 'error' => ''], 200);
    }

    /**
     * delete Salon by ID
     * @param Request $req
     * @param Response $res
     * @param $args
     * @return static
     */
    public function deleteSalon(Request $req, Response $res, $args)
    {
        $salon = Salon::find($args['salon_id']);
        $user = User::where('entry_id', $salon->salon_id)->
        where('entry_type', 'App\Models\Salon')->first();
        $result = $salon->delete();
        $result2 = $user->delete();
        if ($result && $result2) {
            return $res->withJson(['message' => 'OK', 'status' => 'success', 'error' => ''], 200);
        } elseif ($result) {
            return $res->withJson(['message' => 'Salon not deleted', 'status' => 'error', 'error' => 'Something wrong'], 409);
        } elseif ($result2) {
            return $res->withJson(['message' => 'User not deleted', 'status' => 'error', 'error' => 'Something wrong'], 409);
        }

    }

    /**
     * delete any type user by ID
     * @param Request $req
     * @param Response $res
     * @param $args
     * @return static
     */
    public function delUserById(Request $req, Response $res, $args)
    {

        if (isset($args['customer_id'])) {
            $entry_id = $args['customer_id'];
            $customer = Customer::where('customer_id', $entry_id)->first();
            $queue = Queue::where('customer_id', $customer->customer_id)->delete();
            $user = $customer->user();
            $customer->delete();
            $user->delete();
            return $res->withJson(['message' => 'Customer successfully deleted !', 'status' => 'success', 'error' => ''])->withStatus(200);
        } elseif (isset($args['worker_id'])) {
            $entry_id = $args['worker_id'];
            $worker = Worker::where('worker_id', $entry_id)->first();
            $salon = $worker->salon;
            $user = $worker->user();
            $queue = Queue::where('worker_id', $worker->worker_id)->get();
            foreach ($queue as $value){
                Notification::create([
                    'title' =>  'Your queue is canceled.',
                    'message' => 'The worker deleted his account from the system.So your queue at'.$value['time'].' is canceled.',
                    'status' => FALSE,
                    'queue_id' => $value['queue_id'],
                    'user_id' => $value['user_id'],
                ]);
                $value->delete();
            }            
            $worker->delete();
            if ($user->delete()) {
                $salon->staff_number = intval($salon->staff_number) - 1;
                $salon->save();
            }
            return $res->withJson(['message' => 'Worker successfully deleted !', 'status' => 'success', 'error' => ''])->withStatus(200);
        } elseif (isset($args['salon_id'])) {

            $entry_id = $args['salon_id'];
            $salon = Salon::where('salon_id', $entry_id)->first();
            $user = $salon->user();
            $workers = $salon->workers();
            foreach ($workers as $worker) {
                $queue = Queue::where('worker_id', $worker->worker_id)->get();
                foreach ($queue as $value){
                    Notification::create([
                        'title' =>  'Your queue is canceled.',
                        'message' => 'The employee deleted his account from the system. So your queue at'.$value['time'].' is canceled.',
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
            return $res->withJson(['message' => 'Salon successfully deleted !', 'status' => 'success', 'error' => ''])->withStatus(200);
        }elseif (isset($args['user_id'])) {
          $user = User::find($args['user_id']);
          $entry = explode('\\', $user->entry_type)[2];

          if ($entry == 'Worker'){
            $worker = $user->worker;
            $worker->delete();
            $user->delete();
            return $res->withJson(['message' => 'Worker successfully deleted !', 'status' => 'success', 'error' => ''])->withStatus(200);
          }elseif ($entry == 'Customer') {
            $customer = Customer::find($user['entry_id']);
            $customer->delete();
            $user->delete();
            return $res->withJson(['message' => 'Customer successfully deleted !', 'status' => 'success', 'error' => ''])->withStatus(200);
          }elseif ($entry == 'Salon'){
            $salon = $user->salon;
            $workers = $salon->workers();
            foreach ($workers as $worker) {
              $worker->delete();
            }
            $salon->delete();
            $user->delete();
            return $res->withJson(['message' => 'Salon successfully deleted !', 'status' => 'success', 'error' => ''])->withStatus(200);
          }elseif ($entry == 'Admin') {
            $admin = Admin::find($user->entry_id);
            $admin->delete();
            $user->delete();
            return $res->withJson(['message' => 'Admin successfully deleted !', 'status' => 'success', 'error' => ''])->withStatus(200);
          }
        }
        return $res->withJson(['message' => 'User type not found', 'status' => 'error', 'error' => '400'])->withStatus(400);
    }

    /**
     * Change Salon status
     * Active - Salon participate in the search
     * Inactive - Salon does not participate in the search
     * @param Request $req
     * @param Response $res
     * @param $args
     * @return static
     */
    public function changeStatus(Request $req, Response $res, $args)
    {
        $salon = Salon::find($args['salon_id']);
        if ($salon->status == 'Active') {
            $salon->status = 'Inactive';
        } elseif ($salon->status == 'Inactive') {
            $salon->status = 'Active';
        } else {
            $salon->status = 'Inactive';
        }
        $salon->save();
        return $res->withJson(['message' => 'OK!!', 'status' => 'success', 'error' => ''], 200);

    }
}