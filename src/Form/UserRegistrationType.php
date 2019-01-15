<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civility', ChoiceType::class, [
                'choices' => [
                    'Mme' => 0,
                    'Mr' => 1
                ]])
            ->add('pseudo', TextType::class, [
                'attr' => [
                    'placeholder' => 'pseudo'
                ]
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => 'lastName'
                ]
            ])
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => 'firstName'
                ]
            ])
            ->add('street', TextType::class, [
                'attr' => [
                    'placeholder' => 'street'
                ]
            ])
            ->add('city', CountryType::class, [

            ])
            ->add('complement', TextType::class, [
                'attr' => [
                    'placeholder' => 'complement'
                ],
                'required'=> false
            ])
            ->add('postalCode', TextType ::class, [
                'attr' => [
                    'placeholder' => 'postalCode'
                ]
            ])
            ->add('phoneNumber', TelType::class, [
                'attr' => [
                    'placeholder' => 'phoneNumber'
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'johndoe@gmail.com',
                ]
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'placeholder' => 'password'
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'allow_extra_fields'=> false
        ]);
    }
}