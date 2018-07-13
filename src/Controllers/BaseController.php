<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 18.10.2016
 * Time: 17:34
 */
namespace App\Controllers;

use duncan3dc\Laravel\BladeInstance;
use Slim\Container;

class BaseController
{
    protected $ci;
    protected $errors;
    protected $config;
    protected $messages;
    protected $admin;


    public function __construct(Container $ci)
    {
        $this->ci       = $ci;
        $this->errors   = $this->locale['errors'];
        $this->messages = $this->locale['messages'];
        $this->admin    = $this->locale['admin'];
        $this->config   = $this->config_data;

    }

    public function __get($method)
    {
        if($this->ci->{$method})
            return $this->ci->{$method};
    }
}