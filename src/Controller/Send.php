<?php

namespace App\Controller;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Send
{
    /**
     * @Route("/send", name="send")
     */
    public function sendAction()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest','guest');

        $channel = $connection->channel();

        $channel->queue_declare('bonjour', false, false, false, false);

        $msg = new AMQPMessage('hello World');

        $channel->basic_publish($msg, '','bonjour');

        echo "[x] Envoyé 'Bonjour tout le monde'\n";

        $channel->close();

        $connection->close();

        return new Response('OK');
    }


}