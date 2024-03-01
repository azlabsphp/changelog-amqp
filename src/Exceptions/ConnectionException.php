<?php

namespace Drewlabs\Changelog\Rabbitmq\Exceptions;

use Exception;
use Throwable;

class ConnectionException extends Exception
{
    /**
     * Create connection exception instance
     * 
     * @param string $messsage 
     * @param int $code 
     * @param null|Throwable $e 
     * @return void 
     */
    public function __construct(string $messsage, int $code, Throwable $e = null)
    {
        parent::__construct($messsage, $code, $e);
    }
}
