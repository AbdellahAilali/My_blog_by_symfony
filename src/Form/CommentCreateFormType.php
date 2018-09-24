<?php

/**@todo changer les namespace**/
namespace App\Form;
use App\Entity\Comment;
use App\Entity\User;
use function Sodium\add;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class CommentFormType.
 */
class CommentCreateFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'The field "title" should be not blank.'])
                ]
            ])
            ->add("description", TextType::class, [
                 'constraints' => [
                    new NotBlank(['message' => 'The field "description" should be not blank.'])
                ]
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'constraints' => [
                    new NotBlank(['message' => 'The field "user_id" should be not blank.'])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }
}