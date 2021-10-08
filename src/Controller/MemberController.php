<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\Account;
use App\Form\MemberType;
use App\Entity\Accounting;
use App\Manager\MemberManager;
use App\Helper\ParamsInService;
use App\Manager\AccountManager;
use App\Repository\UserRepository;
use App\Repository\MemberRepository;
use App\Repository\SeasonRepository;
use App\Repository\AccountRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class MemberController extends AbstractController
{
    protected $memberRepository;
    protected $userRepository;
    protected $manager;
    protected $params;

    public function __construct(MemberRepository $memberRepository, UserRepository $userRepository, MemberManager $manager, ParamsInService $params)
    {
        $this->memberRepository = $memberRepository;
        $this->userRepository = $userRepository;
        $this->manager = $manager;
        $this->params = $params;
    }

    #[Route('/membres', name: 'member')]
    public function index(): Response
    {
        $user = $this->userRepository->find($this->getUser());
        $members = $user->getMembers();

        return $this->render('member/index.html.twig', [
            'members' => $members
        ]);
    }

    #[Route('/membre/edit/{id?}', name: 'member_add')]
    public function add($id, Request $request, AccountManager $accountManager, SeasonRepository $seasonRepository): Response
    {
        if (!$id) {
            $member = new Member;
        } else {
            $member = $this->memberRepository->find($id);
            if (!$this->getUser()->getMembers()->contains($member)) {
                $this->addFlash('danger', 'Tu n\'es pas autorisé à modifier ce membre !');
                return $this->redirectToRoute('home');
            }
        }
        $form = $this->createForm(MemberType::class, $member);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $member->addUser($this->getUser());
            $this->manager->save($member);
            $accountManager->createAccount('411' . str_pad($member->getId(), 3, '0', STR_PAD_LEFT), 'Membre ' . $member->getFirstName() . ' ' . $member->getLastName());
            $season = $seasonRepository->findOneBy(['enrollmentStatus' => 1]);
            if ($season) {
                return $this->redirectToRoute('enrollment_member', [
                    'id' => $season->getId(),
                    'firstName' => $member->getFirstName(),
                    'lastName' => $member->getLastName()
                ]);
            }
            return $this->redirectToRoute('enrollment');
        }

        return $this->render('member/add.html.twig', [
            'form' => $form->createView(),
            'member' => $member
        ]);
    }

    #[Route('/membre/compte/{id}', name: 'member_account')]
    public function accountHistory(Member $member, AccountRepository $accountRepository)
    {
        if (!$this->getUser()->getMembers()->contains($member)) {
            $this->addFlash('danger', 'Tu n\'es pas autorisé à voir ce membre !');
            return $this->redirectToRoute('home');
        }

        $accountNB = $this->params->get(ParamsInService::APP_ACCOUNTPREFIX_MEMBER) . str_pad($member->getId(), 3, '0', STR_PAD_LEFT);
        /** @var Account */
        $account = $accountRepository->findOneBy(['number' => $accountNB]);
        /** @var Accounting */
        $lines = $account->getAccountings();


        $debit = 0;
        $credit = 0;

        foreach ($lines as $line) {
            $debit += $line->getDebit();
            $credit += $line->getCredit();
        }

        return $this->render('member/account.html.twig', [
            'member' => $member,
            'lines' => $lines,
            'debit' => $debit,
            'credit' => $credit
        ]);
    }
}
