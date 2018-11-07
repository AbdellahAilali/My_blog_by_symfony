<?php

namespace App\Form;

use function Sodium\add;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class UserFormType.
 */
class UserFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'The field "lastname" should be not blank.'])
                ]
            ])
            ->add("firstname", TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'The field "fistname" should be not blank.'])
                ]
            ])
            ->add("birthday", TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'The field "birthday" should be not blank.'])
                ]
            ])
            ->add("photo", FileType::class, [
                'label' => "file the photo"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }
}