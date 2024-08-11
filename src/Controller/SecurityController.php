<?php

namespace App\Controller;

use App\Entity\SiteInformation;
use App\Entity\User;
use App\Form\RegisterFormType;
use App\Service\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class SecurityController extends AbstractController
{
    public const SCOPES = [
        'google' => []
    ];

    public function __construct(
        private TokenStorageInterface $tokenStorage
    ) {

    }

    #[Route(path: '/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, EmailVerifier $emailVerifier, SessionInterface $session)
    {
        $user = new User();

        $form = $this->createForm(RegisterFormType::class, $user);
        $form->handleRequest($request);
        $siteInfo = $entityManager->getRepository(SiteInformation::class)->findOneBy(['id' => 1]);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->beginTransaction(); // Démarrer la transaction

            try {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $user->getPassword()
                    )
                );

                $user->setRoles(['ROLE_USER']);

                $entityManager->persist($user);
                $entityManager->flush();

                $emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from(new Address($siteInfo->getEmail(), $siteInfo->getSiteName()))
                        ->to($user->getEmail())
                        ->subject('Confirmez votre mail.')
                        ->htmlTemplate('mail/confirmation_email.html.twig')
                );

                $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
                $this->tokenStorage->setToken($token);
                $session->set('_security_main', serialize($token));

                $entityManager->commit(); // Valider la transaction

                $this->addFlash(
                    'success',
                    'Inscription effectuée avec succès'
                );

                return $this->redirectToRoute('app_register');
            } catch (\Exception $e) {
                $entityManager->rollback(); // Annuler la transaction
                $this->addFlash('error', 'Une erreur est survenue lors de votre inscription.');

                return $this->redirectToRoute('app_register');
            }
        }

        return $this->render('security/register.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, EmailVerifier $emailVerifier): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Votre adresse e-mail a été vérifiée.');

        return $this->redirectToRoute('app_register');
    }

    #[Route('/verify/resend', name: 'app_verify_resend_email')]
    public function resendVerifyEmail()
    {
        return $this->render('security/resend_verify_email.html.twig');
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/oauth/connect/{service}', name: 'auth_oauth_login', methods: ['GET'])]
    public function googleLogin(string $service, ClientRegistry $clientRegistry)
    {
        if (!in_array($service, array_keys(self::SCOPES), true)) {
            throw $this->createNotFoundException();
        }

        return $clientRegistry
            ->getClient($service)
            ->redirect(self::SCOPES[$service], []);
    }

    #[Route(path: '/oauth/check/{service}', name: 'auth_oauth_check', methods: ['GET', 'POST'])]
    public function check()
    {
        return new Response(status: 200);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
