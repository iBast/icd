<?php

namespace App\Form;

use App\Entity\Race;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'évènement',
                'attr' => [
                    'placeholder' => 'Est-ce le Flying\'Doc Bike & Run ?'
                ]
            ])
            ->add('date', DateType::class, [
                'label' => 'Date de l\'évènement'
            ])
            ->add('location', TextType::class, [
                'label' => 'Lieu',
                'required'   => false,
                'attr' => [
                    'placeholder' => 'Est-ce a Dannemarie ?'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Informations / courses',
                'required'   => false,
                'attr' => [
                    'placeholder' => 'Tu peux donner ici des informations, les horaires de départ, les différentes courses proposées sur l\'évènement',
                    'rows' => 10
                ]
            ])
            ->add('link', UrlType::class, [
                'label' => 'Lien',
                'required'   => false,
                'attr' => [
                    'placeholder' => 'https://ironclub.blog/bike-run'
                ]
            ])
            ->add('signInLink', UrlType::class, [
                'label' => 'Lien d\'inscription',
                'required'   => false,
                'attr' => [
                    'placeholder' => 'https://www.sporkrono.fr/event/flying-doc/'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Race::class,
        ]);
    }
}
