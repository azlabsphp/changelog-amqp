<?php

namespace Drewlabs\Changelog\Amqp;

interface ConnectionInterface
{
    /**
     * Get a message channel on which read and write operations are performed
     * 
     * @param int|null $channel_id 
     * @return Channel 
     */
    public function getChannel(int $channel_id = null): Channel;

}