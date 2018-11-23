<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Cache\InvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use spec\Tolerance\Metrics\Collector\RabbitMq\RabbitMqCollectorSpec;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Tolerance\Metrics\Collector\RabbitMq\RabbitMqHttpClient;
use Twig\Environment;

class CacheController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Template
     */
    private $template;
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Environment $template
     * @param AdapterInterface $adapter
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Environment $template,
        AdapterInterface $adapter
    )
    {
        $this->entityManager = $entityManager;
        $this->template = $template;
        $this->adapter = $adapter;
    }

    /**
     * @Route("/cached", name="cached")
     * @return Response
     * @throws InvalidArgumentException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function cachedData()
    {
        var_dump(RabbitMqCollectorSpec::class);
        
        echo 'abdellah';
        echo 'ailali';
        $userItem = $this
            ->adapter
            ->getItem('users');

        if (!$userItem->isHit()) {
                echo "mark1";
            /** @var EntityRepository $repo */
            $repo = $this->entityManager
                ->getRepository(User::class);

            $users = $repo
                ->createQueryBuilder('u')
                ->getQuery()
                ->getScalarResult();

            $userItem->set(json_encode($users));
            $this->adapter->save($userItem);
        }

        return new Response($this
            ->template
            ->render('base.html.twig',["users"=>$userItem]));
    }
}