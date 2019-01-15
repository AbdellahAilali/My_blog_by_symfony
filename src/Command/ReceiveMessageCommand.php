<?php

namespace App\Command;

use App\Entity\User;
use App\Service\ReceiveMessageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Bernard\Consumer;
use Bernard\Driver\Amqp\Driver;
use Bernard\Message\PlainMessage;
use Bernard\Producer;
use Bernard\QueueFactory\PersistentFactory;
use Bernard\Router\ReceiverMapRouter;
use Bernard\Serializer;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method receiveMessageService(string $string)
 */
class ReceiveMessageCommand extends Command
{
    /**
     * @var ReceiveMessageService $receiveMessageService
     */
    private $receiveMessageService;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    public function __construct(ReceiveMessageService $receiveMessageService, EntityManagerInterface $entityManager, AMQPStreamConnection $connection)
    {
        $this->receiveMessageService = $receiveMessageService;
        $this->entityManager = $entityManager;
        parent::__construct();

        $this->connection = $connection;
    }

    protected function configure()
    {
        $this
            ->setName('app.receive.message')
            ->setDescription('Consume the message')
            ->setHelp('This command allows you consume  message');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $driver = new Driver($this->connection, 'my');

        $router = new ReceiverMapRouter([

            'messEmail' => function ($message) {

                echo "oh yes", var_dump($message);
            },
        ]);

        $evenDispatcher = new EventDispatcher();

        $consumer = new Consumer($router, $evenDispatcher);

        $persistentFactory = new PersistentFactory($driver, new Serializer());

        $consumer->consume($persistentFactory->create('email'));
    }

}