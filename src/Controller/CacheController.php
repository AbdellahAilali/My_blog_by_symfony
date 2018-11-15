<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     */
    public function cachedData()
    {
        $userItem = $this->adapter->getItem('users');

        if (!$userItem->isHit()) {

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

        return new Response($this->template->render('base.html.twig'));
    }
}