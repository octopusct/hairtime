<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 15.10.2016
 * Time: 19:59
 */
return [
    'settings' => [
        'determineRouteBeforeAppMiddleware' => true,
        'devMode' => true,
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'username' => 'oshrihairtime_api',
            'password' => 'tBo1246Mks',
            /*'username' => 'devacc',
            'password' => 'passfordev',*/
            'database' => 'oshrihairtime_main',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => ''
        ],

    ]
];