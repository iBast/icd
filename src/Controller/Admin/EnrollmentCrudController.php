<?php

namespace App\Controller\Admin;

use App\Entity\Enrollment;
use App\Manager\EnrollmentManager;
use App\Repository\SeasonRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterConfigDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDto;

class EnrollmentCrudController extends AbstractCrudController
{

    protected $seasonRepository;

    public function __construct(SeasonRepository $seasonRepository)
    {
        $this->seasonRepository = $seasonRepository;
    }

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
            ->showEntityActionsInlined(true);
    }

    public function configureFilters(Filters $filters): Filters
    {
        $season = $this->seasonRepository->findOneBy(['enrollmentStatus' => 1]);
        return $filters
            ->add(NullFilter::new('endedAt', 'Dossier Complet')->setChoiceLabels('Non', 'Oui'))
            ->add(ChoiceFilter::new('status')->setChoices(Enrollment::STATUS))
            ->add(EntityFilter::new('Season', 'Saison'));
    }

    public function configureActions(Actions $actions): Actions
    {
        // this action executes the 'renderInvoice()' method of the current CRUD controller
        $validate = Action::new('validate', 'Valider les documents')
            ->linkToCrudAction('validate')->setCssClass('btn btn-success');

        $paymentOk = Action::new('paymentOk', 'Paiement fait')
            ->linkToCrudAction('paymentOk')->setCssClass('btn btn-success');

        $sendEmail = Action::new('missingEmail', 'Relance mail')->linkToCrudAction('missingEmail')->setCssClass('btn btn-warning');

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, $validate)
            ->add(Crud::PAGE_INDEX, $validate)
            ->add(Crud::PAGE_DETAIL, $validate)
            ->add(Crud::PAGE_EDIT, $paymentOk)
            ->add(Crud::PAGE_INDEX, $paymentOk)
            ->add(Crud::PAGE_DETAIL, $paymentOk)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_EDIT, Action::DELETE)
            ->add(Crud::PAGE_DETAIL, $sendEmail)
            ->setPermissions(['pending' => 'ROLE_ADHESIONS', 'validate' => 'ROLE_ADHESIONS', 'paymentOk' => 'ROLE_TRESORIER']);
    }


    public function validate(AdminContext $context, EnrollmentManager $manager, AdminUrlGenerator $adminUrlGenerator)
    {
        /** @var Enrollment */
        $enrollment = $context->getEntity()->getInstance();
        $manager->validate($enrollment);
        $this->addFlash('success', 'Les documents ont étés validés');
        return $this->redirect($adminUrlGenerator->setController(EnrollmentCrudController::class)->setAction(Action::INDEX)->generateUrl());
    }

    public function paymentOk(AdminContext $context, EnrollmentManager $manager, AdminUrlGenerator $adminUrlGenerator)
    {
        /** @var Enrollment */
        $enrollment = $context->getEntity()->getInstance();
        $manager->paymentOk($enrollment);
        $this->addFlash('success', 'Le paiement a été validé');
        return $this->redirect($adminUrlGenerator->setController(EnrollmentCrudController::class)->setAction(Action::INDEX)->generateUrl());
    }

    public function missingEmail(AdminContext $context, EnrollmentManager $manager, AdminUrlGenerator $adminUrlGenerator)
    {
        $enrollment = $context->getEntity()->getInstance();
        $manager->sendEmailMissingDocs($enrollment);
        $this->addFlash('success', 'L\'email a bien été envoyé');
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
            BooleanField::new('hasPhotoAuthorization', 'Autorisation de publication de photos')->hideOnIndex(),
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
