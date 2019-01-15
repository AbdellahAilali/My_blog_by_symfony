<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Asset\Package;

class AssetTest extends Controller
{
    /**@Route("/asset", name="asset_page")
     * @param Package $package
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction(Package $package)
    {
        echo $package->getUrl('/uploads/brochure/image.jpeg');
        echo $package->getUrl('uploads/brochure/image.jpeg');

        return $this->render('test.html.twig');
    }

    /*
    public function formRegistrationAction(Request $request)
    {
        $formRegistration = $this
            ->formFactory->create(UserRegistrationType::class);

        $userRegistration = $formRegistration->handleRequest($request);

        $dataRegistration = $userRegistration->getData();
        /** @var UserRegistration $email */
    //$email = $this->entityManager->getRepository(UserRegistration::class)->findBy(["email" => $dataRegistration['email']]);
    /* $email = $this->entityManager->getRepository(UserRegistration::class)->findAll();

     $tabEmailUser = array();

     foreach ($email as $value) {

         $tabEmailUser [] = $value->getEmail();
         $tabPseudoUser [] = $value->getPseudo();
     }

     if ($formRegistration->isSubmitted() && $formRegistration->isValid()) {

         if (in_array($dataRegistration['pseudo'], $tabPseudoUser)) {

             echo 'ce pseudo à déjà étè utiliser';

         } else if (!in_array($dataRegistration['email'], $tabEmailUser)) {

             $this->formManager->createUser(
                 $dataRegistration['civility'],
                 $dataRegistration['pseudo'],
                 $dataRegistration['lastName'],
                 $dataRegistration['firstName'],
                 $dataRegistration['street'],
                 $dataRegistration['city'],
                 $dataRegistration['postalCode'],
                 $dataRegistration['phoneNumber'],
                 $dataRegistration['email'],
                 $dataRegistration['password'],
                 $dataRegistration['complement']);

             echo 'success';
             return $this->render('form/index.html.twig', ['form' => $formRegistration->createView()]);
         }
         else {
             echo 'cette adresse email à déjà étè utilise';
         }
     }

     return $this->render('form/index.html.twig', ['form' => $formRegistration->createView()]);

 }*/


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