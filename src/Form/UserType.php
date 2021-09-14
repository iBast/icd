<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'help' => 'En cas de modification de l\'email, vous devrez valider le changement par email',
                'constraints' => [new NotBlank(['message' => 'Indiquez un email'])]
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Votre prénom',
                'constraints' => [new NotBlank(['message' => 'Indiquez un prénom'])],
                'attr' => [
                    'placeholder' => 'Votre prénom'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Votre nom',
                'constraints' => [new NotBlank(['message' => 'Indiquez un nom'])],
                'attr' => [
                    'placeholder' => 'Votre nom'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
