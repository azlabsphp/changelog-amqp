<?php

namespace Drewlabs\Changelog\Rabbitmq;

class Message implements Messageable
{
    /**
     * @var string
     */
    private $topic;

    /**
     * @var string
     */
    private $table;

    /**
     * @var string|int
     */
    private $instance;

    /**
     * @var string
     */
    private $column;

    /**
     * @var mixed
     */
    private $previous;

    /**
     * @var mixed
     */
    private $current;

    /**
     * @var string
     */
    private $by;


    /**
     * Creates a rabbit mq message
     * @param string|object $message 
     * @param string $exchange 
     * @param string $topic 
     * @return void 
     */
    public function __construct(string $topic = '')
    {
        $this->topic = $topic;
    }

    /**
     * Creates new message instance
     * 
     * @param string $topic 
     * @return static 
     */
    public static function new(string $topic = '')
    {
        return new static($topic);
    }

    /**
     * immuatable `table` property value setter
     * 
     * @param string $name 
     * @return static 
     */
    public function withTable(string $name)
    {
        $self = clone $this;
        $self->table = $name;
        return $self;
    }

    /**
     * immuatable `instance` property value setter
     * 
     * @param string $name 
     * @return static 
     */
    public function withInstance(string $id)
    {
        $self = clone $this;
        $self->instance = $id;
        return $self;
    }

    /**
     * immuatable `table` property value setter
     * 
     * @param string $name 
     * @return static 
     */
    public function withColumn(string $name)
    {
        $self = clone $this;
        $self->column = $name;
        return $self;
    }

    /**
     * immuatable `previous value` property value setter
     * 
     * @param mixed $value 
     * @return static 
     */
    public function withPreviousValue($value = null)
    {
        $self = clone $this;
        $self->previous = $value;
        return $self;
    }

    /**
     * immuatable `current value` property value setter
     * 
     * @param mixed $value 
     * @return static 
     */
    public function withCurrentValue($value)
    {
        $self = clone $this;
        $self->current = $value;
        return $self;
    }

    /**
     * immuatable `logged by` property value setter
     * 
     * @param string|null $name 
     * @return static 
     */
    public function withLogBy(string $value = null)
    {
        $self = clone $this;
        $self->by = $value;
        return $self;
    }

    /**
     * Returns the AMQ exchange routing key
     * 
     * @return string 
     */
    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function __toString(): string
    {
        return json_encode([
            'table' => $this->table,
            'instance' => $this->instance,
            'property' => $this->column,
            'previous_value' => $this->previous,
            'current_value' => $this->current,
            'log_by' => $this->by,
            'log_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
