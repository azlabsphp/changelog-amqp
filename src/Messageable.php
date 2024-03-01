<?php

namespace Drewlabs\Changelog\Amqp;

interface Messageable
{
    /**
     * Returns the AMQ exchange routing key
     * 
     * @return string 
     */
    public function getTopic(): ?string;

    /**
     * Returns string representation of the message instance
     * 
     * @return string 
     */
    public function __toString(): string;
}