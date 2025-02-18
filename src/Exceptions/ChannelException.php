<?php

namespace Drewlabs\Changelog\Amqp\Exceptions;

use Exception;
use Throwable;

class ChannelException extends Exception
{
    /**
     * creates exception class
     */
    public function __construct(string $message, int $code, ?\Throwable $throwable = null)
    {
        parent::__construct($message, $code, $throwable);
    }

}