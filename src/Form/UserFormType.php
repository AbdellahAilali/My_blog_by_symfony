<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('lastName', TextType::class)
            ->add("firstName", TextType::class);
           /* ->add("birthday", TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'The field "birthday" should be not blank.'])
                ]
            ])
            ->add("photo", FileType::class, [
                'label' => "file the photo"
            ]);*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }
}