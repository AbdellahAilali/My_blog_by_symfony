<?php

namespace App\Controller;

use App\Form\CommentCreateFormType;
use App\Form\CommentFormType;
use App\Manager\CommentManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CommentController
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var CommentManager
     */
    private $commentManager;

    /**
     * @param CommentManager         $commentManager
     * @param FormFactoryInterface   $formFactory
     */
    public function __construct(CommentManager $commentManager, FormFactoryInterface $formFactory)
    {
        $this->commentManager = $commentManager;
        $this->formFactory = $formFactory;
    }

    /**
     * @Route("/comment", name="add_comment", methods={"POST"})
     *
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
        $this->commentManager->createComment($id, $data['title'], $data['description'], $data['user']) ;

        return new JsonResponse(['id' => $id, 'title' => $data['title'], 'description' => $data['description'], 'user' => $data['user']->getId()]);
    }

    /**
     * @route("/modify_comment/{id}", name="modify_comment", methods={"PUT"})
     *
     * @param Request $request
     * @param         $id
     *
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

        $this->commentManager->modifyComment(
            $id,
            $data['title'],
            $data['description']
        );

        return new JsonResponse(array_merge(['id'=>$id],$data));
    }


    /**
     * @route("/delete_comment/{id}", name="delete_comment", methods={"DELETE"})
     *
     * @return JsonResponse
     * @param $id
     */

    public function deleteCommentAction($id)
    {
        try {
            $this->commentManager->deleteComment($id);
        } catch (NotFoundHttpException $exception) {

            return new JsonResponse($exception->getMessage(),
                $exception->getStatusCode());
        }

        return new JsonResponse();

    }
}