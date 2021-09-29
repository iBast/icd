<?php

namespace App\Form;

use App\Entity\Licence;
use App\Entity\EnrollmentYoung;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class EnrollmentYoungType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('licence', EntityType::class, [
                'label' => 'Adhésion',
                'placeholder' => '-- Choisir le type d\'adhésion --',
                'class' => Licence::class,
                'choice_label' => 'name'
            ])
            ->add('hasPoolAcces', CheckboxType::class, [
                'label' => 'Accès piscine',
                'required' => false
            ])
            ->add('hasCareAuthorization', CheckboxType::class, [
                'label' => 'J\'autorise les responsables de l’Iron Club Dannemarie à faire soigner, à faire procéder en cas d’urgence à toute intervention médicale ou chirurgicale que nécessiterait l’état de santé de mon fils, ma fille (ou autre lien de parenté)',
                'required' => false
            ])
            ->add('hasPhotoAuthorization', CheckboxType::class, [
                'label' => 'J\'autorise l\' Iron Club de Dannemarie à utiliser les photos de mon enfant prise pendant les compétitions/Entrainement/stage pour ses plaquettes d\' information, article de presse, et tout support numérique (site web, page facebook)',
                'required' => false
            ])
            ->add('hasLeaveAloneAuthorization', CheckboxType::class, [
                'label' => 'J\'autorise mon fils/fille à quitter la séance d’entrainement du samedi tout(e) seul(e)',
                'required' => false
            ])
            ->add('hasTreatment', CheckboxType::class, [
                'label' => 'Votre enfant suit-il un traitement médical spécifique ?',
                'required' => false
            ])
            ->add('treatmentDetails', TextType::class, [
                'label' => 'si oui le quel',
                'required' => false
            ])
            ->add('hasAllergy', CheckboxType::class, [
                'label' => 'Votre enfant est-il allergique ?',
                'required' => false
            ])
            ->add('AllergyDetails', TextType::class, [
                'label' => 'Si oui, précisez la cause de l’allergie et la conduite à tenir (si automédication le signaler)',
                'required' => false
            ])
            ->add('emergencyContact', TextType::class, [
                'label' => 'La personne à contacter en cas d\'urgence est :'
            ])
            ->add('emergencyPhone', TextType::class, [
                'label' => 'Son numéro de téléphone :'
            ])
            ->add('medicalFile', VichFileType::class, [
                'label' => 'Certificat médical',
                'required' => false
            ])
            ->add('FFTriDocFile', VichFileType::class, [
                'label' => 'Document transmit par la FF TRI',
                'required' => false
            ])
            ->add('antiDopingFile', VichFileType::class, [
                'label' => 'Attestation anti dopage',
                'required' => false
            ])
            ->add('paymentMethod', ChoiceType::class, [
                'label' => 'Mode de règlement',
                'placeholder' => '-- Choisir le mode de règlement --',
                'choices' => [
                    'Virement (à privilégier)' => 'Virement',
                    'Espèces (à donner dans une enveloppe a son nom)' => 'Espèces',
                    'Chèque' => 'Chèque'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EnrollmentYoung::class,
        ]);
    }
}
