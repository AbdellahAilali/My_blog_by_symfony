<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Template;

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
     * @param EntityManagerInterface $entityManager
     * @param Template               $template
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Template $template
    )
    {
        $this->entityManager = $entityManager;
        $this->template = $template;
    }


    /**
     * @Route("/cached", name="cached")
     */
    public function cachedData()
    {
        $client = RedisAdapter::createConnection(
            'redis://localhost'
        );

        /** @var RedisAdapter $cache */
        $cache = new RedisAdapter(
            $client,
            $namespace = '',
            $defaultLifetime = 10
        );

        $userItem = $cache->getItem('users');

        if (!$userItem->isHit()) {

            echo 'user n\'est pas cache';

            /** @var EntityRepository $repo */
            $repo = $this->entityManager
                ->getRepository(User::class);

            $users = $repo
                ->createQueryBuilder('u')
                ->getQuery()
                ->getScalarResult();

            $userItem->set(json_encode($users));
            $cache->save($userItem);

        } else {
            echo 'user est en cache';
        }

        return $this->template->render(['base.html.twig']);
    }
}