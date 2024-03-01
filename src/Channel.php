<?php

namespace Drewlabs\Changelog\Amqp;

interface Channel
{
    /**
     * Broadcast a message to the Message Queue server using the provided message broker.
     * 
     * If a queue name is provided, the message is 
     * 
     * @param Messageable $message 
     * @param string $broker 
     * @param string|null $queue 
     * @return void 
     * @throws ChannelException 
     */
    public function broadcast(Messageable $message, string $broker, string $queue = null);

    /**
     * Send a message to a given channel queue
     * 
     * @param Messageable $message 
     * @param string $broker 
     * @param string $queue 
     * @return void 
     */
    public function send(Messageable $message, string $broker, string $queue): void;
}
