<?php

namespace App\Service;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class SendMessageService
{
    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * SendMessageService constructor.
     * @param AMQPStreamConnection $connection
     */
    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
    }

    public function send($message, $routingKey)
    {
        $channel = $this->connection->channel();

        $channel->queue_declare($routingKey, false, false, false, false);

        $msg = new AMQPMessage($message);

        $channel->basic_publish($msg, '',$routingKey);

        $channel->close();

        $this->connection->close();
    }
}