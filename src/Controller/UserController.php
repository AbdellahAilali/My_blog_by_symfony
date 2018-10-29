<?php

namespace App\Controller;
/*header('Access-Control-Allow-Origin: *');*/

use App\Entity\User;
use App\Form\UserFormType;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager, FormFactoryInterface $formFactory)
    {
        $this->userManager = $userManager;
        $this->formFactory = $formFactory;
    }

    /**
     * @return JsonResponse
     * @Route ("/", name="user_all", methods={"GET"})
     */
    public function loadAllUserAction()
    {
        $tabUser = [];
        try {
            $tabUser = $this->userManager->loadAllUser();
        } catch (NotFoundHttpException $exception) {

            return new JsonResponse(['error_message' =>
                $exception->getMessage()],
                $exception->getStatusCode());
        }

        return new JsonResponse($tabUser);
    }

    /**
     * @Route ("/user/{id}", name="load_user", methods={"GET"})
     *
     * @param string $id
     *
     * @return JsonResponse
     */

    public function loadUserAction($id)
    {
        try {
            $result = $this->userManager->loadUser($id);

        } catch (NotFoundHttpException $exception) {

            return new JsonResponse(['error_message' =>
                $exception->getMessage()],
                $exception->getStatusCode());
        }

        return new JsonResponse($result);
    }


    /**
     * @Route ("/user_delete/{id}", name="user_delete", methods={"DELETE"})
     * @param $id
     * @return JsonResponse
     */
    public function deleteUserAction($id)
    {
        /** @var User $user */
        try {
            $this->userManager->deleteUser($id);
        } catch (NotFoundHttpException $exception) {

            return new JsonResponse(['error_message' =>
                $exception->getMessage()],
                $exception->getStatusCode());
        }

        return new JsonResponse();
    }


    /**
     * @Route ("/user", name="create_user", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createUserAction(Request $request)
    {
        /**je crée mon formlaire a partir de ma class UserFormType
         * Je le soumette et lui envoye ma request
         * true comme 2param pour qu'il me renvoie un tab associatif*/

        $form = $this
            ->formFactory->create(UserFormType::class);

        $form
            ->submit(json_decode($request->getContent(), true));

        $data = $form->getData();
        if (!$form->isValid()) {
            return new JsonResponse([(string)
            $form->getErrors(true)], 400);
        }

        $id = uniqid();
        $this->userManager->createUser(
            $id,
            $data['firstname'],
            $data['lastname'],
            new \DateTime($data['birthday']));

        return new JsonResponse(array_merge(['id' => $id], $data));
    }

    /**
     * @Route("/user/modify/{id}", name="modify_user",methods={"PUT"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */

    public function modifyUserAction(Request $request, $id)
    {
        $form = $this->formFactory->create(UserFormType::class);
        $form->submit(json_decode($request->getContent(), true));

        $data = $form->getData();

        if (!$form->isValid()) {
            return new JsonResponse([(string)
            $form->getErrors(true)], 400);
        }
        $this->userManager->modifyUser($id,
            $data['firstname'],
            $data['lastname'],
            new \DateTime($data['birthday']));

        return new JsonResponse(array_merge(['id' => $id], $data));
    }


}