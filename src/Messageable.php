<?php

namespace Drewlabs\Changelog\Amqp;

interface Messageable
{
    /**
     * returns the AMQ exchange routing key
     * 
     * @return string 
     */
    public function getTopic(): ?string;

    /**
     * returns string representation of the message instance
     * 
     * @return string 
     */
    public function __toString(): string;
}