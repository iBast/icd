<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\SeasonRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Markup;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(SeasonRepository $seasonRepository): Response
    {
        if ($this->getUser() && false === $this->getUser()->isVerified()) {
            $message = new Markup(
                $this->renderView('message/_emailFlash.html.twig', ['user' => $this->getUser()]),
                'UTF-8'
            );
            $this->addFlash('warning', $message);
        }

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $season = $seasonRepository->findOneBy(['enrollmentStatus' => 1]);

        return $this->render('home/index.html.twig', [
            'season' => $season,
        ]);
    }

    #[Route('/mon-compte', name: 'account')]
    public function account(Request $request, EmailVerifier $emailVerifier, EntityManagerInterface $em): Response
    {
        /** @var User */
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getEmail() !== $user->getEmail()) {
                $user->setIsVerified(false);
                $emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from(new Address('ironclub.dannemarie@gmail.com', 'Iron Club'))
                        ->to($user->getEmail())
                        ->subject('Confirmez votre adresse email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );
            }
            $em->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Modification du compte enregistrée');

            return $this->redirectToRoute('account');
        }

        return $this->render('home/account.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
