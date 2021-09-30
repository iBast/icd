<?php

namespace App\Form;

use App\Entity\Member;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Prénom du membre'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom du membre'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => [
                    'placeholder' => 'Email du membre',
                    'type' => 'email'
                ]
            ])
            ->add('adress', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Adresse du membre'
                ]
            ])
            ->add('postCode', TextType::class, [
                'label' => 'CP',
                'attr' => [
                    'placeholder' => 'CP'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Ville'
                ]
            ])
            ->add('mobile', TextType::class, [
                'label' => 'Téléphone portable',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Portable du membre',
                    'type' => 'tel'

                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone fixe',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Fixe du membre',
                    'type' => 'tel'
                ]
            ]);

        if ($options) {
            $builder->add('birthday', BirthdayType::class, [
                'label' => 'Date de naissance'
            ]);
        } else {
            $builder->add('birthday', BirthdayType::class, [
                'label' => 'Date de naissance',
                'data' => new DateTime('1990-06-15')
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}
