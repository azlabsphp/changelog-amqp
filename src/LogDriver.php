<?php

namespace Drewlabs\Changelog\Rabbitmq;

use Drewlabs\Changelog\LogDriver as AbstractLogLogDriver;

class LogDriver implements AbstractLogLogDriver
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var string
     */
    private $topic;

    /**
     * @var string
     */
    private $broker;

    /**
     * Driver constructor
     * 
     * @param ConnectionInterface $connection 
     */
    public function __construct(ConnectionInterface $connection, string $topic = '*', string $broker = 'tables_changelogs')
    {
        $this->connection = $connection;
        $this->topic = $topic ?? '*';
        $this->broker = $broker ?? 'table_changelogs';
    }

    public function logChange(string $table, string $instance, string $property, $previous, $actual, ?string $logBy = null)
    {
        $this->connection->getChannel()
            ->broadcast(Message::new($this->topic)
                ->withInstance(strval($instance))
                ->withTable($table)
                ->withColumn($property)
                ->withPreviousValue($previous)
                ->withCurrentValue($actual)
                ->withLogBy($logBy), $this->broker);
    }
}
