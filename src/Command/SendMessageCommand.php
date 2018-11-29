<?php

namespace App\Command;

use App\Service\SendMessageService;
use Bernard\Driver\Amqp\Driver;
use Bernard\Message\PlainMessage;
use Bernard\Producer;
use Bernard\QueueFactory\PersistentFactory;
use Bernard\Serializer;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SendMessageCommand extends Command
{
    /**
     * @var SendMessageService $messageService
     */
    private $messageService;
    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * @param SendMessageService $messageService
     * @param AMQPStreamConnection $connection
     */
    public function __construct(SendMessageService $messageService, AMQPStreamConnection $connection)
    {
        $this->messageService = $messageService;
        $this->connection = $connection;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app.send.message')
            ->setDescription('Send a message with rabbit')
            ->setHelp('This command allows you to send message ');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //$this->messageService->send('{"id":"00001", "firstname":"james","lastname":"bond","birthday":"01-01-1999"}', 'userQueue');

        //Connection au server
        $driver = new Driver($this->connection, 'my-exchange');

        //Cree des queues et les récupérer par rapport au driver utilisé
        $factory = new PersistentFactory($driver, new Serializer());

        //Produire le message
        $producer = new Producer($factory, new EventDispatcher());

        //Cree le message avec un nom et un tab arguments
        $message = new PlainMessage('messEmail', array(
            'abdela47@gmail.com' => 12,
        ));

        //Envoie le messaga
        $producer->produce($message, 'email');
    }
}