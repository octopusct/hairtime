<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 15.10.2016
 * Time: 18:17
 */


if (PHP_SAPI == 'cli-server') {
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    if (is_file($file)) {
        return false;
    }
}

require_once __DIR__ . '/../vendor/autoload.php';
if (file_exists(__DIR__.'/../src/config_local.php')) {
    $config = require_once __DIR__ . '/../src/config_local.php';
}else{
    $config = require_once __DIR__ . '/../src/config.php';
}

ini_set('date.timezone', 'Asia/Jerusalem');
date_default_timezone_set('Asia/Jerusalem');

$app = new \Slim\App($config);


require __DIR__ . '/../src/dependencies.php';
require __DIR__ . '/../src/routes.php';

$app->add(function (\Slim\Http\Request $req, \Slim\Http\Response $res, $next) {
    $res = $next($req, $res);
    return $res->withAddedHeader('Access-Control-Allow-Origin', '*')
            ->withAddedHeader('Access-Control-Allow-Credentials', 'true')
            ->withAddedHeader('Access-Control-Allow-Methods', 'GET, POST, PUT ,DELETE, OPTIONS')
            ->withAddedHeader('Access-Control-Max-Age', '1000')
            ->withAddedHeader('Access-Control-Allow-Headers', 'Content-Type, Content-Range, Content-Disposition, Content-Description')
            ->withStatus(200);
});

$app->run();