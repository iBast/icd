<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form;

use App\Entity\Enrollment;
use App\Entity\Licence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class EnrollmentStep1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('licence', EntityType::class, [
                'label' => 'Choix de la licence',
                'placeholder' => '-- Choisir le type d\'adhésion --',
                'class' => Licence::class,
                'choice_label' => 'name',
            ])
            ->add('hasPoolAcces', CheckboxType::class, [
                'label' => 'Accès piscine',
                'required' => false,
            ])
            ->add('hasPhotoAuthorization', CheckboxType::class, [
                'label' => 'Droit à l\'image - J\'autorise l’Iron Club de Dannemarie à utiliser des photos pendant les compétitions/Entrainement/stage pour ses plaquettes d’information, article de presse, et tout support numérique (site web, page Facebook)',
                'required' => false,
            ])
            ->add('medicalFile', VichFileType::class, [
                'label' => 'Certificat médical',
                'required' => false,
            ])
            ->add('FFTriDocFile', VichFileType::class, [
                'label' => 'Document transmis par la FF TRI',
                'required' => false,
            ])
            ->add('FFTriDoc2File', VichFileType::class, [
                'label' => '2ème page (si besoin)',
                'required' => false,
            ])
            ->add('paymentMethod', ChoiceType::class, [
                'label' => 'Mode de règlement',
                'placeholder' => '-- Choisir le mode de règlement --',
                'choices' => [
                    'Virement (à privilégier)' => 'Virement',
                    'Espèces (à donner dans une enveloppe a son nom)' => 'Espèces',
                    'Chèque' => 'Chèque',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enrollment::class,
        ]);
    }
}
