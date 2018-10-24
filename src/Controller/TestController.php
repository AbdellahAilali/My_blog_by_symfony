<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TestController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/test" , name="test")
     * @return mixed
     *
     * @return Response
     * @throws \Exception
     */
    public function testAction()
    {
        $id = uniqid();
        $commentId =uniqid();
        $user = new User($id, 'Ailali', 'jamel', new \DateTime('23-12-1993'));
        $comments = new Comment($commentId, 'My titre', 'My description', $user);

        $user->addComment($comments);

        $result = [];
        $result ['id'] = $user->getId();
        $result ['lastName'] = $user->getLastname();
        $result ['firstName'] = $user->getFirstname();
        $result ['date'] = $user->getBirthday();


        $tabComment = [];
        foreach ($user->getComments() as $comment){

            $tabComment= [

                $tabComment['id'] = $comment->getId(),
                $tabComment['title'] = $comment->getTitle(),
                $tabComment['description'] = $comment->getDescription(),
                $tabComment['userId'] = $comment->getUser(),
            ];
        }
        $this->entityManager->persist($user);

        $this->entityManager->flush();

        return $this->render('test.html.twig', [
            'result' => $result
        ]);
    }
}

