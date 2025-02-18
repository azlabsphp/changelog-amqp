<?php

namespace Drewlabs\Changelog\Amqp;

use Drewlabs\Changelog\Amqp\Exceptions\ConnectionException;
use PhpAmqpLib\Connection\AMQPConnectionConfig;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AbstractConnection;

class Connection implements ConnectionInterface
{
    /** @var AbstractConnection */
    private $rabbit;

    /** @var float */
    private $connection_timeout = 3.0;

    /** @var float */
    private $read_write_timeout = 3.0 ;

    /** @var mixed */
    private $server;

    /**
     * Creates new connection instance
     * 
     * @param Server $server 
     * @param null|AMQPConnectionConfig $config 
     * @return void 
     */
    public function __construct(Server $server, ?AMQPConnectionConfig $config = null)
    {
        $this->server = $server;
        // By default the channel will use a lazy connection to the
        // RabbitMQ server
        if (null === $config) {
            $config = new AMQPConnectionConfig;
            $config->setIsLazy(true);
        }
        $this->initializeConnection($config);
    }

    /**
     * Initialize a rabbit mq connection
     * 
     * @param AMQPConnectionConfig|null $config 
     * @return void 
     */
    private function initializeConnection(?AMQPConnectionConfig $config = null)
    {
        $this->rabbit = new AMQPStreamConnection(
            $this->server->getHost(),
            $this->server->getPort(),
            $this->server->getUser(),
            $this->server->getPassword(),
            $this->server->getVirtualHost(),
            false,
            'AMQPLAIN',
            null,
            'en_US',
            $this->connection_timeout,
            $this->read_write_timeout,
            null,
            false,
            0,
            0.0,
            $config
        );
    }

    public function getChannel(?int $channel_id = null): RabbitmqChannel
    {
        try {
            return new RabbitmqChannel($this->rabbit->channel($channel_id));
        } catch (\Throwable $e) {
            throw new ConnectionException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function __destruct()
    {
        if (null !== $this->rabbit) {
            $this->rabbit->__destruct();
        }
        unset($this->server);
    }
}
