<?php

namespace App\Controller;

use App\Entity\UserRegistration;
use App\Form\UserRegistrationType;
use App\Manager\FormRegistrationManager;
use Doctrine\ORM\EntityManagerInterface;
use mageekguy\atoum\locale;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    /**
     * @var FormFactory
     */
    public $formFactory;
    /**
     * @var EntityManagerInterface
     */
    public $entityManager;
    /**
     * @var FormRegistrationManager
     */
    public $formManager;

    /**
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     * @param FormRegistrationManager $formManager
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        FormRegistrationManager $formManager)
    {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->formManager = $formManager;
    }

    /**
     * @Route("/form", name="browserkit_page")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function formRegistrationAction(Request $request)
    {
        $userRegistration = new UserRegistration();

        $formRegistration = $this->formFactory->create(UserRegistrationType::class, $userRegistration);

        $formRegistration->handleRequest($request);

        if ($formRegistration->isSubmitted() && $formRegistration->isValid()) {

           $this->entityManager->persist($userRegistration);
           $this->entityManager->flush();

            echo 'success';
            return $this->render('form/index.html.twig', ['form' => $formRegistration->createView()]);
        }
        return $this->render('form/index.html.twig', ['form' => $formRegistration->createView()]);

    }


    /* public function formConnexionAction(Request $request)
    {
       $formConnexion = $this
            ->formFactory->create(UserConnexionType::class);

        $userAlreadyAccount = $formConnexion->handleRequest($request);

        $dataUserConnex = $userAlreadyAccount->getData();

       if ($formConnexion->isSubmitted() && $formConnexion->isValid())
            $user = $this->entityManager
                ->getRepository(UserRegistration::class)
                ->findOneBy(["pseudo" => $dataUserConnex['pseudo']]);

            if (!empty($user)) {
                echo "mark1";

                if ($dataUserConnex['pseudo'] == $user->getPseudo() && $dataUserConnex === $user->getPassword()) {
                    echo "mark2";
                    return new Response("Bienvenu " . $dataUserConnex['pseudo']);
                }

                echo "votre mots de pass ou votre pseudo ne correspond pas!";
            }
    }*/

}
