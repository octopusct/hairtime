<?php
//session_start();
/**
 * Created by PhpStorm.
 * User: javelin
 * Date: 25.01.2017
 * Time: 21:36
 */

use App\Middlewares\AdminChecker;
use App\Middlewares\AjaxMiddlewares;
use App\Middlewares\AuthChecker;
use App\Middlewares\PermissionChecker;
use App\Middlewares\SalonChecker;
//use App\Middlewares\routeMiddleware;
use App\Models\NToken;
use Slim\Http\Request;
use Slim\Http\Response;


$app->map(['get','options'], '/', App\Controllers\HomeController::class);
// $app->post('/', App\Controllers\HomeController::class)->add(new //PermissionChecker(['customer', 'worker', 'admin']));

$app->group('/del', function () {
    $this->delete('', 'App\Controllers\AuthController:delUser');
    $this->delete('/customer/{customer_id:[0-9]*}', 'App\Controllers\AuthController:delUserById')
        ->add(new PermissionChecker(['customer', 'admin']));
    $this->delete('/worker/{worker_id:[0-9]*}', 'App\Controllers\AuthController:delUserById')
        ->add(new PermissionChecker(['salon', 'worker', 'admin']));
    $this->delete('/salon/{salon_id:[0-9]*}', 'App\Controllers\AuthController:delUserById')
        ->add(new PermissionChecker(['salon', 'admin']));

})->add(new AuthChecker());

$app->get('/recalccomments', 'App\Controllers\CommentController:recalc');

$app->get('/forgot_password/{email}', 'App\Controllers\AuthController:forgotPassword');


$app->map(['options', 'post', 'get'], '/telegram', 'App\Components\TelegramBot:index');

$app->group('/queue', function () {
    $this->get('/salon/{salon_id}/service/{service_id}/{date}', 'App\Controllers\QueueController:salonServiceFreeTime');
    $this->get('/worker/{worker_id}', 'App\Controllers\QueueController:getQueue');
    $this->get('/salon/{salon_id}', 'App\Controllers\QueueController:getSalonQueue');
    $this->get('/customer/{customer_id}', 'App\Controllers\QueueController:getCustomerQueue');
    $this->post('/{worker_id}/{service_id}', 'App\Controllers\QueueController:addQueue');
    $this->delete('/{queue_id}', 'App\Controllers\QueueController:deleteQueue');
    $this->get('/confirm/{queue_id}', 'App\Controllers\QueueController:confirmQueue');
    $this->get('/worker/freetime/{worker_id}/{date}', 'App\Controllers\QueueController:freeTime');
    $this->get('/salon/freetime/{salon_id}/{date}', 'App\Controllers\QueueController:salonFreeTime');
})->add(new AuthChecker());


$app->group('/notification', function () {
    $this->get('/{user_id}', 'App\Controllers\NotificationController:getNotification');
    $this->map(['post', 'options'], '', 'App\Controllers\NotificationController:setStatus');
});

//    $app->group('/notification', function () {
//    $this->post('/set_token', function (Request $req, Response $res) {
//        $user = \App\Models\User::where('user_id', ($req->getParam('user_id')))->first();
//        $ntoken = new NToken();
//        $ntoken->n_token = $req->getParam('n_token');
//        $ntoken->user_id = $user->user_id;
//        $ntoken->save();
//        return $res->withJson($ntoken)->withStatus(201);
//    });
//    $this->post('', 'App\Controllers\NotificationController:tryNotification');
//
//});

$app->group('/auth', function () {
    $this->group('/singup', function () {
        $this->map(['post','options'], '/customer', 'App\Controllers\AuthController:singupCustomer');
        $this->map(['post','options'], '/salon', 'App\Controllers\AuthController:singupSalon');
        $this->group('/worker', function () {
            $this->map(['post','options'], '/start', 'App\Controllers\AuthController:startWorker');
            $this->map(['post','options'], '/complete', 'App\Controllers\AuthController:singupWorker');
        });
    });
    $this->group('/edit', function () {
        $this->map(['post','options'], '/customer', 'App\Controllers\AuthController:editCustomer');
        $this->map(['post','options'], '/salon', 'App\Controllers\AuthController:editSalon');
        $this->map(['post','options'], '/worker', 'App\Controllers\AuthController:editWorker');
        $this->map(['post','options'], '/worker/{worker_id:[0-9]*}', 'App\Controllers\AuthController:editWorker');
    })->add(new AuthChecker());
    $this->map(['get','options'], '/makeme/{user_id:[0-9]*}', 'App\Controllers\AuthController:makeMe');
    $this->map(['get','options'], '/confirm_email/{user_id:[0-9]*}', 'App\Controllers\AuthController:confirmEmail');
    $this->map(['post', 'get','options'], '/forgot_password', 'App\Controllers\AuthController::forgotPassword');
    $this->map(['post','options'], '/singin', 'App\Controllers\AuthController:singin');
    $this->map(['post','options'], '/singout', 'App\Controllers\AuthController:singout')->add(new AuthChecker());
    $this->map(['post','options'], '/newPassword', 'App\Controllers\AuthController:newPassword')->add(new AuthChecker());
})/*-> add(new \App\Middlewares\routeMiddlewares())*/;

$app->map(['post','options'], '/upload', 'App\Controllers\UploadController:uploadFile');


$app->any('/login', 'App\Controllers\AdminController:login');

$app->group('/message', function () {
    $this->map(['post', 'options'], '', 'App\Controllers\AdminController:messageToAdmin');
    $this->map(['post', 'options'], '/user', 'App\Controllers\AdminController:messageToUser');
});

$app->group('/ajax', function () {
    $this->group('/worker', function () {
        $this->post('/delete/{worker_id:[0-9]*}', 'App\Controllers\AjaxController:delUserById');
    });
    $this->group('/salon', function () {
        $this->post('/status/{salon_id:[0-9]*}', 'App\Controllers\AjaxController:changeStatus');
        $this->post('/delete/{salon_id:[0-9]*}', 'App\Controllers\AjaxController:delUserById');
    });
    $this->group('/customer', function () {
      $this->post('/delete/{customer_id:[0-9]*}', 'App\Controllers\AjaxController:delUserById');
    });
    $this->group('/user', function () {
      $this->post('/delete/{user_id:[0-9]*}', 'App\Controllers\AjaxController:delUserById');
    });
    $this->group('/service', function () {
      $this->post('/delete/{service_id:[0-9]*}', 'App\Controllers\AjaxController:delService');
    });

})->add (new AjaxMiddlewares());

$app->group('/admin', function () {
    $this->get('/empty', 'App\Controllers\AdminController:emptyPage');
    $this->any('', 'App\Controllers\AdminController:salons');
    $this->any('/', 'App\Controllers\AdminController:salons');
    $this->group('/worker', function () {
        $this->get('', 'App\Controllers\AdminController:workerList');
        $this->get('/{worker_id:[0-9]*}', 'App\Controllers\AdminController:getWorker');
        $this->post('/{worker_id:[0-9]*}', 'App\Controllers\AdminController:saveWorker');
    });

    $this->group('/customer', function () {
        $this->get('', 'App\Controllers\AdminController:customerList');
        $this->get('/{customer_id:[0-9]*}', 'App\Controllers\AdminController:getCustomer');
        $this->post('/{customer_id:[0-9]*}', 'App\Controllers\AdminController:setCustomer');
    });
    $this->group('/service', function () {
        $this->get('', 'App\Controllers\AdminController:servicesList');
        $this->get('/{service_id:[0-9]*}', 'App\Controllers\AdminController:getServices');
    });
    $this->group('/message', function () {
        $this->post('', 'App\Controllers\AdminController:message');
        $this->get('', 'App\Controllers\AdminController:getAllMessage');
        $this->get('/new', 'App\Controllers\AdminController:getNewMessage');
        $this->any('/{message_id:[0-9]*}', 'App\Controllers\AdminController:workWithMessage');
        $this->post('/newpass/{user_id:[0-9]*}', 'App\Controllers\AdminController:newPassword');
    });
    $this->group('/api', function () {
        $this->get('', 'App\Controllers\ApiController:api');
    });
    $this->post('/login', 'App\Controllers\AdminController:login');
    $this->get('/singin', 'App\Controllers\AdminController:singin');
    $this->get('/logout', 'App\Controllers\AdminController:logout');
    $this->group('/salon', function () {
        $this->post('/{salon_id:[0-9]*}', 'App\Controllers\AdminController:editSalon');
        $this->get('/{salon_id:[0-9]*}', 'App\Controllers\AdminController:getSalon');
        $this->get('/new', 'App\Controllers\AdminController:newSalon');

    });
    $this->get('/profile', 'App\Controllers\AdminController:profile');
    $this->get('/profile/{admin_id:[0-9]*}', 'App\Controllers\AdminController:profile');
    $this->get('/comments', 'App\Controllers\AdminController:comments');
    $this->post('/comments', 'App\Controllers\AdminController:comments');
    $this->post('/comments/{comment_id:[0-9]*}', 'App\Controllers\AdminController:comments');
});


$app->group('/dispatcher', function () {
    $this->get('', 'App\Controllers\DispatcherController:getDispatcher');
    $this->post('', 'App\Controllers\DispatcherController:postDispatcher');
});

//$app->get('/public[/css/{[a-z]+}]', '');

$app->group('/worker', function () {
    $this->group('/schedule/{worker_id:[0-9]*}', function () {
        $this->get('', 'App\Controllers\ScheduleController:getSchedule');
        //$this->options('', 'App\Controllers\ScheduleController:newSchedule');
        $this->map(['post', 'options'],'', 'App\Controllers\ScheduleController:newSchedule')
            ->add(new PermissionChecker(['admin','worker','salon']))
            ->add(new AuthChecker());
        $this->delete('/{schedule_id:[0-9]*}', 'App\Controllers\ScheduleController:deleteSchedule')
            ->add(new PermissionChecker(['admin','worker','salon']))
            ->add(new AuthChecker());
    });
    $this->map(['post','options'], '/schedules/{worker_id:[0-9]*}', 'App\Controllers\ScheduleController:newJSONSchedule')
        ->add(new PermissionChecker(['admin','worker','salon']))
        ->add(new AuthChecker());

});

$app->group('/service', function () {
    $this->group('/salon', function () {
        $this->group('/{salon_id:[0-9]*}', function () {
            $this->get('', 'App\Controllers\ServiceController:getBySalon');
            $this->post('', 'App\Controllers\ServiceController:newService')
                ->add(new PermissionChecker(['salon', 'admin']));
            $this->put('/{service_id:[0-9]*}', 'App\Controllers\ServiceController:edit')
                ->add(new PermissionChecker(['salon', 'admin']));
            $this->delete('/{service_id:[0-9]*}', 'App\Controllers\ServiceController:delete')
                ->add(new PermissionChecker(['salon', 'admin']));
            $this->post('/upload/{service_id:[0-9]*}', 'App\Controllers\UploadController:uploadService')
                ->add(new PermissionChecker(['salon', 'worker', 'admin']));
        });
    });
    $this->group('/worker', function () {
        $this->group('/{worker_id:[0-9]*}', function () {
            $this->get('', 'App\Controllers\ServiceController:getByWorker');
            $this->post('/{service_id:[0-9]*}', 'App\Controllers\ServiceController:newByWorker')
                ->add(new PermissionChecker(['worker', 'salon', 'admin']))->add(new AuthChecker());
            $this->put('/{service_id:[0-9]*}', 'App\Controllers\ServiceController:editByWorker')
                ->add(new PermissionChecker(['worker', 'salon', 'admin']))->add(new AuthChecker());
            $this->delete('/{service_id:[0-9]*}', 'App\Controllers\ServiceController:deleteByWorker')
                ->add(new PermissionChecker(['worker', 'salon', 'admin']))->add(new AuthChecker());
        });
    });
});

$app->group('/salon', function () {
    /*$this->group('/service', function () {
        $this->group('/{salon_id:[0-9]*}', function () {
            $this->post('', 'App\Controllers\ServiceController:new');
            $this->get('', 'App\Controllers\ServiceController:getBySalon');
            $this->get('/{worker_id:[0-9]*}', 'App\Controllers\ServiceController:getByWorker');
            $this->put('/{service_id:[0-9]*}', 'App\Controllers\ServiceController:edit');
            $this->delete('/{service_id:[0-9]*}', 'App\Controllers\ServiceController:delete');
        });
    });*/
    $this->get('/isactiv/{salon_id:[0-9]*}', function (Request $req, Response $res) {
        return $res->withJson(['ddd'=>'ddd'], 200);

    });
    $this->group('/workers', function () {
        $this->map(['get', 'options'], '/{salon_id:[0-9]*}', 'App\Controllers\WorkerController:getWorkers');

        $this->map(['get', 'options'], '/service/{worker_id:[0-9]*}', 'App\Controllers\WorkerController:getWorkersService');
    })->add(new AuthChecker());

    $this->group('/search', function () {
//        $this->any('/{lat:[-]?[0-9]{1,3}\,[0-9]{6}}/{lng:[-]?[0-9]{1,3}\,[0-9]{6}}/{radius:[0-9]{2,10}}',
////            'App\Controllers\SearchController:aroundSearch'
//            function(Request $req, Response $res, $args) {
//             return $res->withJson(['ddd'=>'ddd'], 200);
//            }
//);


        $this->get('/{city}', 'App\Controllers\SearchController:freeSearch');
        $this->get('/{lat}/{lng}/{radius}', 'App\Controllers\SearchController:aroundSearch');
    });
    $this->group('/rating', function () {
        $this->group('/{salon_id:[0-9]*}', function () {
            $this->post('', 'App\Controllers\RatingController:newRate');
            $this->get('', 'App\Controllers\RatingController:get');
            $this->get('/salonrate', 'App\Controllers\RatingController:getSalonRate');
        });
    })->add(new AuthChecker());
    $this->group('/comments', function () {
        $this->group('/{salon_id:[0-9]*}', function () {
            $this->map(['post', 'options'], '', 'App\Controllers\CommentController:new')
                ->add(new PermissionChecker([ 'customer', 'admin']));
            $this->get('', 'App\Controllers\CommentController:get')
                ->add(new PermissionChecker(['worker', 'salon', 'admin', 'customer']));
            $this->put('/{comment_id:[0-9]*}', 'App\Controllers\CommentController:edit')
                ->add(new PermissionChecker([ 'customer', 'admin']));
            $this->delete('/{comment_id:[0-9]*}', 'App\Controllers\CommentController:delete')
                ->add(new PermissionChecker(['customer', 'admin']));
        });
    })->add(new AuthChecker());
});

$app->group('/manage', function () {
    $this->group('/account', function () {

    });

    $this->group('/salon', function () {
        $this->group('/workers', function () {
            $this->get('', 'App\Controllers\WorkerController:get');
            $this->post('', 'App\Controllers\WorkerController:add');
            $this->put('/{worker_id:[0-9]*}', 'App\Controllers\WorkerController:edit');
            $this->delete('/{worker_id:[0-9]*}', 'App\Controllers\WorkerController:delete');
        });
    })->add(new PermissionChecker('salon'));
})->add(new AuthChecker());

