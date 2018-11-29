<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ReceiveMessageService
{
    /**
     * @var AMQPStreamConnection
     */
    private $connection;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param AMQPStreamConnection $connection
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(AMQPStreamConnection $connection, EntityManagerInterface $entityManager)
    {
        $this->connection = $connection;
        $this->entityManager = $entityManager;
    }

    public function receive($routingKey)
    {
        $chanel = $this->connection->channel();

        $chanel->queue_declare($routingKey, false, false, false, false);

        echo "Waiting for message. To exit press CTRL+C\n";


        $callback = function ($tabUser) {

            $tabUser = json_decode($tabUser->body, true);

            $user = new User($tabUser["id"], $tabUser["firstname"], $tabUser["lastname"], new \DateTime($tabUser["birthday"]));

            $this->entityManager->persist($user);

            $this->entityManager->flush();
        };

        $chanel->basic_consume($routingKey, '', false, true, false, false, $callback);

        while (count($chanel->callbacks)) {

            $chanel->wait();
        }
    }
}