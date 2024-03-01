<?php

namespace Drewlabs\Changelog\Amqp\Exceptions;

use Exception;
use Throwable;

class ChannelException extends Exception
{
    /**
     * Creates exception class
     * @param Throwable $throwable 
     */
    public function __construct(string $message, int $code, \Throwable $throwable)
    {
        parent::__construct($message, $code, $throwable);
    }

}