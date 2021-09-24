<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;
use App\Manager\AccountManager;
use App\Manager\MemberManager;
use App\Repository\MemberRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[IsGranted('ROLE_USER')]
class MemberController extends AbstractController
{
    protected $memberRepository;
    protected $userRepository;
    protected $manager;

    public function __construct(MemberRepository $memberRepository, UserRepository $userRepository, MemberManager $manager)
    {
        $this->memberRepository = $memberRepository;
        $this->userRepository = $userRepository;
        $this->manager = $manager;
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

    #[Route('/membres/edit/{id?}', name: 'member_add')]
    public function add($id, Request $request, AccountManager $accountManager): Response
    {
        if (!$id) {
            $member = new Member;
        } else {
            $member = $this->memberRepository->find($id);
        }
        $form = $this->createForm(MemberType::class, $member);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $member->addUser($this->getUser());
            $this->manager->save($member);
            $accountManager->createAccount('411' . str_pad($member->getId(), 3, '0', STR_PAD_LEFT), 'Membre ' . $member->getFirstName() . ' ' . $member->getLastName());
            return $this->redirectToRoute('enrollment');
        }

        return $this->render('member/add.html.twig', [
            'form' => $form->createView(),
            'member' => $member
        ]);
    }
}
