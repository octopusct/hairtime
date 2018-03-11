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

$container['notFoundHandler'] = function ($c) {
    $req_uri = explode('admin', $_SERVER['REQUEST_URI']);
    if (isset($req_uri[1])) {
        $blade = new BladeInstance(__DIR__ . "/../public/Views", __DIR__ . "/../public/Views/cache");
        echo $blade->render("404page");
        return ;
    } else {
        return function (\Slim\Http\Request $req, \Slim\Http\Response $res) {
            return $res->withStatus(404);
        };
    }
};

$container['notAllowedHandler'] = function ($c) {
    return function (\Slim\Http\Request $req, \Slim\Http\Response $res) {
        return $res->withStatus(405); //TODO: turn off debug
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

$container['phpErrorHandler'] = function ($c) {
    return function (\Slim\Http\Request $req, \Slim\Http\Response $res, Exception $e) use ($c) {
        return $res->write($e->getMessage());
    };
};


$container['errorHandler'] = function ($c) {
    return function (\Slim\Http\Request $req, \Slim\Http\Response $res, Exception $e) use ($c) {
        return $res->write($e->getMessage());
    };
};