<?php

namespace Drewlabs\Changelog\Amqp;

use Drewlabs\Changelog\Amqp\Exceptions\ChannelException;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitmqChannel implements Channel
{
    /**
     * @var AMQPChannel
     */
    private $channel;

    /**
     * Creates new channel instance
     * 
     * @param AMQPChannel $channel
     * 
     * @return void 
     */
    public function __construct(AMQPChannel $channel)
    {
        $this->channel = $channel;

        register_shutdown_function(function () {
            if (null !== $this->channel && $this->channel->is_open()) {
                $this->channel->close();
            }
        });
    }

    /**
     * Broadcast a message to the Message Queue server using the provided message broker.
     * 
     * If a queue name is provided, the message is 
     * 
     * @param Messageable $message 
     * @param string $broker 
     * @param string|null $queue
     * 
     * @return void 
     * @throws ChannelException 
     */
    public function broadcast(Messageable $message, string $broker, ?string $queue = null)
    {
        try {
            $this->channel->confirm_select();

            if (!empty($queue)) {
                $this->selectQueue($queue);
                $this->channel->queue_bind($queue, $broker, $message->getTopic());
            }

            // Declare exchange if it does not exists
            $this->channel->exchange_declare($broker, AMQPExchangeType::FANOUT, false, true, false);

            // Publish message on the queue
            $this->channel->basic_publish(
                new AMQPMessage($message->__toString(), [
                    'content_type' => 'text/plain',
                    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
                ]),
                $broker,
                $message->getTopic()
            );

            // Wait for server to send ack and drop if timeout
            $this->channel->wait_for_pending_acks(5);
        } catch (\Throwable $e) {
            throw new ChannelException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function send(Messageable $message, string $broker, string $queue): void
    {
        try {
            $this->channel->confirm_select();
            $this->selectQueue($queue);
            $this->channel->queue_bind($queue, $broker, $message->getTopic());
            $this->channel->exchange_declare($broker, $message->getTopic(), false, true, false);

            $this->channel->basic_publish(
                new AMQPMessage($message->__toString(), [
                    'content_type' => 'text/plain',
                    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
                ]),
                $broker,
                $message->getTopic()
            );

            // Wait for server to send ack and drop if timeout
            $this->channel->wait_for_pending_acks(5);
        } catch (\Throwable $e) {
            throw new ChannelException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Select a queue on which read an write operations are performed
     * 
     * @param string $queue 
     * @return void 
     * @throws AMQPTimeoutException 
     */
    private function selectQueue(?string $queue = null)
    {
        // Making sure the queue exist before consuming from it
        $this->channel->queue_declare($queue ?? '', false, true, false, false);
    }
}
