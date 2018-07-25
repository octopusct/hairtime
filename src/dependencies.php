<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 15.10.2016
 * Time: 21:31
 */

use duncan3dc\Laravel\BladeInstance;
use Slim\Http\Request as Request;

$container = $app->getContainer();

$res = new \Slim\Http\Response();


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
    $file_handler = new \Monolog\Handler\StreamHandler(__DIR__."/logs/hairtime".date('d-m-Y').".log");
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['VIEW_PATH']= function ($c){
    return __DIR__."/../public/Views";
};

$container['BASE_URL']= function ($c){
    return $_SERVER['SERVER_NAME'];
};

$container['PREFIX']= function ($c){
    if ($c['settings']['prefix']) return $c['settings']['prefix']; else return '';
};

$container['config_data']= function ($container){
    return $container['settings'];
};


/**
 * @param Request $req
 * @return array
 */
$container['locale'] = function()use($container,$res){
    if (isset($_REQUEST['lang'])){
        $file_name =$_REQUEST['lang'];
        $main_lang =  json_decode(file_get_contents(__DIR__.'/lang/'.$container['settings']['lang'].'.json'), true);
        $load_lang =  json_decode(file_get_contents(__DIR__.'/lang/'.$file_name.'.json'), true);
        $result_lang = array_replace_recursive($main_lang, $load_lang);
    } elseif (isset($_COOKIE['lang'])){
        $file_name = $_COOKIE['lang'];
        $main_lang =  json_decode(file_get_contents(__DIR__.'/lang/'.$container['settings']['lang'].'.json'), true);
        $load_lang =  json_decode(file_get_contents(__DIR__.'/lang/'.$file_name.'.json'), true);
        $result_lang = array_replace_recursive($main_lang, $load_lang);

    }else{
        $result_lang =  json_decode(file_get_contents(__DIR__.'/lang/'.$container['settings']['lang'].'.json'), true);
    }
    return $result_lang;

};

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
            return $res->withJson(['message'=>'Page not found', 'success'=>false, 'error'=>404])->withStatus(404);
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
            ->write('MESSAGE: '.$e->getMessage().' FILE: '.$e->getFile().' LINE: '.$e->getLine());
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