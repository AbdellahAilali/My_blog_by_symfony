<?php
/**
 * Created by PhpStorm.
 * User: abdellah
 * Date: 19/07/18
 * Time: 16:59
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentCreateFormType;
use App\Manager\CommentManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use App\Form\CommentFormType;

class CommentController
{

    /**
     * @var FormFactoryInterfacex
     */
    private $formFactory;
    /**
     * @var CommentManager
     */
    private $commmentManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(CommentManager $commmentManager, FormFactoryInterface $formFactory)
    {
        $this->commmentManager = $commmentManager;
        $this->formFactory = $formFactory;
    }

    /**
     * @Route("/comment", name="add_comment", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createCommentAction(Request $request)
    {
        $form = $this->formFactory->create(CommentCreateFormType::class);
        $form->submit(json_decode($request->getContent(), true));

        $data = $form->getData();
        if (!$form->isValid()) {
            return new JsonResponse([(string)$form->getErrors(true)], 400);
        }

        $id = uniqid();
        $this->commmentManager->createComment($id, $data['title'], $data['description'], $data['user']) ;

        return new JsonResponse(['id' => $id, 'title' => $data['title'], 'description' => $data['description'], 'user' => $data['user']->getId()]);
    }

    /**
     * @todo  modifier nom des routes, rajouter une sortie erreur
     */
    /**
     * @route("/modify_comment/{id}", name="modify_comment", methods={"PUT"})
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */

    public function modifyCommentAction(Request $request, $id)
    {
        $form = $this->formFactory->create(CommentFormType::class);
        $form->submit(json_decode($request->getContent(), true));

        $data = $form->getData();

        if (!$form->isValid()) {
            return new JsonResponse([(string)
            $form->getErrors(true)], 400);
        }

        $this->commmentManager->modifyComment(
            $id,
            $data['title'],
            $data['description']
        );

        return new JsonResponse(array_merge(['id'=>$id],$data));
    }


    /**
     * @route("/delete_comment/{id}", name="delete_comment", methods={"DELETE"})
     * @return JsonResponse
     * @param $id
     */

    public function deleteCommentAction($id)
    {
        try {
            $this->commmentManager->deleteComment($id);
        } catch (NotFoundHttpException $exception) {

            return new JsonResponse($exception->getMessage(),
                $exception->getStatusCode());
        }


        return new JsonResponse();

    }
}