<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 15.10.2016
 * Time: 21:31
 */

use duncan3dc\Laravel\BladeInstance;

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager();
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($c) use ($capsule) {
    return $capsule;
};

/** monolog */
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('hairtime_logger');
    $file_handler = new \Monolog\Handler\StreamHandler(__DIR__."/logs/hairtime.log");
    $logger->pushHandler($file_handler);
    return $logger;
} ;



$container['notFoundHandler'] = function ($c) use ($container){
    $req_uri = explode('admin', $_SERVER['REQUEST_URI']);
    $container['logger']->warning('', array(
        'ERROR_CODE'=>'404',
        'FILE'=>'APP/src/routes.php',
        'MESSAGE'=>'URL: '.$_SERVER['REQUEST_URI'].' Not found',
    ));
    if (isset($req_uri[1])) {
        $blade = new BladeInstance(__DIR__ . "/../public/Views", __DIR__ . "/../public/Views/cache");
        echo $blade->render("404page");
        return ;
    } else {
        return function (\Slim\Http\Request $req, \Slim\Http\Response $res) {
            return $res->withJson(['message'=>'Not found', 'success'=>false, 'error'=>404])->withStatus(404);
        };
    }
};

$container['notAllowedHandler'] = function ($c) use ($container){
    $container['logger']->warning('', array(
    'ERROR_CODE'=>'405',
    'FILE'=>'APP/src/routes.php',
    'MESSAGE'=>'Method not Allow. URL: '.$_SERVER['REQUEST_URI'].' method:'.$_SERVER['REQUEST_METHOD'],
));
    return function (\Slim\Http\Request $req, \Slim\Http\Response $res) {
        return $res->withJson(['message'=>'Method not Allow', 'success'=>false, 'error'=>405])->withStatus(405); //TODO: turn off debug
    };
};


\Respect\Validation\Validator::with('\App\Validation\Rules');

$container['validator'] = function ($c) {
    return new \App\Validation\Validator($c);
};

$container['devMode'] = function () use ($container){
    return $container['settings']['devMode'];
};


//TODO: turn off debug

$container['phpErrorHandler'] = function ($c) use ($container) {
    return function (\Psr\Http\Message\ServerRequestInterface $req, \Psr\Http\Message\ResponseInterface $res, \Throwable $e) use ($c, $container) {
        $container['logger']->error('', array(
            'ERROR_CODE'=>$e->getCode(),
            'FILE'=>$e->getFile(),
            'LINE'=>$e->getLine(),
            'MESSAGE'=>$e->getMessage(),
            ));
        return $c['response']
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write($e->getMessage());
    };
};

$container['errorHandler'] = function ($c) use ($container){
    return function (\Psr\Http\Message\ServerRequestInterface $req, \Psr\Http\Message\ResponseInterface $res, \Throwable $e) use ($c, $container) {
        $container['logger']->error('', array(
            'ERROR_CODE'=>$e->getCode(),
            'FILE'=>$e->getFile(),
            'LINE'=>$e->getLine(),
            'MESSAGE'=>$e->getMessage(),
        ));
        return $c['response']
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write($e->getMessage());
    };
};