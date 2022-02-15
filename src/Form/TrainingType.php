<?php

namespace App\Form;

use App\Entity\Sport;
use App\Entity\Training;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class TrainingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Date de l\'évènement'
            ])
            ->add('content', CKEditorType::class, [
                'config_name' => 'my_config',
                'label' => 'Description de la séance',
                'required'   => true,
                'attr' => [
                    'rows' => 15
                ]
            ])
            ->add('sport', EntityType::class, [
                'label' => 'Sport',
                'placeholder' => '-- Choisir le sport --',
                'class' => Sport::class,
                'choice_label' => 'name'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Training::class,
        ]);
    }
}
