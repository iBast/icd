<?php

namespace App\Controller\Admin;

use App\Entity\Season;
use App\Entity\Enrollment;
use App\Repository\MemberRepository;
use App\Repository\SeasonRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class EnrollmentCrudController extends AbstractCrudController
{
    protected $seasonRepository;
    protected $memberRepository;

    public function __construct(SeasonRepository $seasonRepository, MemberRepository $memberRepository)
    {
        $this->seasonRepository = $seasonRepository;
        $this->memberRepository = $memberRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Enrollment::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $seasons = $this->seasonRepository->findBy(['enrollmentStatus' => 1]);
        $season = [];
        foreach ($seasons as $getseason) {
            $season[$getseason->getYear()] = $getseason;
        }
        return [
            AssociationField::new('memberId', 'Membre'),
            ChoiceField::new('season', 'Saison')->setChoices($season)->onlyOnDetail(),
            TextField::new('status', 'Statut'),
            BooleanField::new('isMember', 'Membre'),
            AssociationField::new('Licence'),
            BooleanField::new('hasPoolAcces', 'Passe piscine'),
            BooleanField::new('hasCareAuthorization', 'Autorisation de soins'),
            BooleanField::new('hasPhotoAuthorization', 'Autorisation de publication de photos'),
            BooleanField::new('hasLeaveAloneAuthorization', 'Autorisation de partir seul'),
            BooleanField::new('hasAllergy', 'A des allergies'),
            TextareaField::new('allergyDetails', 'Détail des allergies'),
            BooleanField::new('hasTreatment', 'Suit un traitement'),
            TextareaField::new('treatmentDetails', 'Détail du traitement'),
            TextField::new('emergencyContact', 'Contact en cas d\'urgence'),
            TelephoneField::new('emergencyPhone', 'Numéro d\'urgence'),
            MoneyField::new('totalAmount', 'Montant total')->setCurrency('EUR'),
            TextField::new('paymentMethod', 'Mode de paiement'),
            DateField::new('paymentAt', 'Date de paiement'),
            DateField::new('createdAt', 'Création de la demande')->onlyOnIndex(),
            DateField::new('endedAt', 'Dossier validé le')
            //filetype : certificat
            //filetype : fftri doc
        ];
    }
}
