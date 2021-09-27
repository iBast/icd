<?php

namespace App\Controller\Admin;

use App\Entity\Enrollment;
use App\Manager\EnrollmentManager;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NullFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class EnrollmentCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Enrollment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Adhésion')
            ->setEntityLabelInPlural('Adhésions')
            ->showEntityActionsInlined();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('Season', 'Saison'))
            ->add(NullFilter::new('endedAt', 'Dossier Complet')->setChoiceLabels('Non', 'Oui'))
            ->add(ChoiceFilter::new('status')->setChoices(Enrollment::STATUS));
    }

    public function configureActions(Actions $actions): Actions
    {
        // this action executes the 'renderInvoice()' method of the current CRUD controller
        $validate = Action::new('validate', 'Valider les documents')
            ->linkToCrudAction('validate')->setCssClass('btn btn-success');

        //$pending = Action::new('pending', 'Dossier Transmis FF Tri')
        //->linkToCrudAction('pending')->setCssClass('btn btn-success');

        $paymentOk = Action::new('paymentOk', 'Paiement fait')
            ->linkToCrudAction('paymentOk')->setCssClass('btn btn-success');

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, $validate)
            ->add(Crud::PAGE_INDEX, $validate)
            ->add(Crud::PAGE_DETAIL, $validate)
            //->add(Crud::PAGE_EDIT, $pending)
            //->add(Crud::PAGE_INDEX, $pending)
            //->add(Crud::PAGE_DETAIL, $pending)
            ->add(Crud::PAGE_EDIT, $paymentOk)
            ->add(Crud::PAGE_INDEX, $paymentOk)
            ->add(Crud::PAGE_DETAIL, $paymentOk)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_EDIT, Action::DELETE)
            ->setPermissions(['pending' => 'ROLE_ADHESIONS', 'validate' => 'ROLE_ADHESIONS', 'paymentOk' => 'ROLE_TRESORIER']);
    }


    public function validate(AdminContext $context, EnrollmentManager $manager, AdminUrlGenerator $adminUrlGenerator)
    {
        /** @var Enrollment */
        $enrollment = $context->getEntity()->getInstance();
        $manager->validate($enrollment);
        return $this->redirect($adminUrlGenerator->setController(EnrollmentCrudController::class)->setAction(Action::INDEX)->generateUrl());
    }

    public function pending(AdminContext $context, EnrollmentManager $manager, AdminUrlGenerator $adminUrlGenerator)
    {
        /** @var Enrollment */
        $enrollment = $context->getEntity()->getInstance();
        $manager->pending($enrollment);
        return $this->redirect($adminUrlGenerator->setController(EnrollmentCrudController::class)->setAction(Action::INDEX)->generateUrl());
    }

    public function paymentOk(AdminContext $context, EnrollmentManager $manager, AdminUrlGenerator $adminUrlGenerator)
    {
        /** @var Enrollment */
        $enrollment = $context->getEntity()->getInstance();
        $manager->paymentOk($enrollment);
        return $this->redirect($adminUrlGenerator->setController(EnrollmentCrudController::class)->setAction(Action::INDEX)->generateUrl());
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('Season', 'Saison'),
            AssociationField::new('memberId', 'Membre'),
            ImageField::new('FFTriDocPath', 'Document FFTri')
                ->onlyOnDetail()
                ->setBasePath($this->getParameter('enrollment_docs')),
            ImageField::new('medicalAuthPath', 'Certificat médical')
                ->onlyOnDetail()
                ->setBasePath($this->getParameter('enrollment_docs')),
            BooleanField::new('isDocsValid', 'Documents Ok'),
            ChoiceField::new('status', 'Statut')->setChoices(fn () => Enrollment::STATUS),
            BooleanField::new('isMember', 'Membre')->hideOnIndex(),
            AssociationField::new('Licence')->hideOnIndex(),
            BooleanField::new('hasPoolAcces', 'Passe piscine')->hideOnIndex(),
            BooleanField::new('hasCareAuthorization', 'Autorisation de soins')->hideOnIndex(),
            BooleanField::new('hasPhotoAuthorization', 'Autorisation de publication de photos')->hideOnIndex(),
            BooleanField::new('hasLeaveAloneAuthorization', 'Autorisation de partir seul')->hideOnIndex(),
            BooleanField::new('hasAllergy', 'A des allergies')->hideOnIndex(),
            TextareaField::new('allergyDetails', 'Détail des allergies')->hideOnIndex(),
            BooleanField::new('hasTreatment', 'Suit un traitement')->hideOnIndex(),
            TextareaField::new('treatmentDetails', 'Détail du traitement')->hideOnIndex(),
            TextField::new('emergencyContact', 'Contact en cas d\'urgence')->hideOnIndex(),
            TelephoneField::new('emergencyPhone', 'Numéro d\'urgence')->hideOnIndex(),
            MoneyField::new('totalAmount', 'Montant total')->setCurrency('EUR')->hideOnIndex(),
            TextField::new('paymentMethod', 'Mode de paiement')->hideOnIndex(),
            DateField::new('paymentAt', 'Date de paiement'),
            DateField::new('createdAt', 'Création de la demande')->hideOnIndex(),
            DateField::new('endedAt', 'Dossier validé le')->hideOnIndex(),
            TextareaField::new('medicalFile', 'Certificat médical')
                ->onlyOnForms()
                ->setFormType(VichImageType::class),
            TextareaField::new('FFTriDocFile', 'Document FFTri')
                ->onlyOnForms()
                ->setFormType(VichImageType::class)
        ];
    }
}
