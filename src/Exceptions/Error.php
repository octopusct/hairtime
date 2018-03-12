<?php
/**
 * Created by PhpStorm.
 * User: javelin
 * Date: 12.03.18
 * Time: 0:26
 */

namespace App\Exceptions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class Error extends \Slim\Handlers\Error
{
    protected $logger;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Request $request, Response $response, \Exception $exception)
    {
        // Log the message
//        $this->logger->critical($exception->getMessage());

        return $response
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write($exception->getMessage());
    }
}