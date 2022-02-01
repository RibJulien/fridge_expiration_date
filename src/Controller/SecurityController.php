<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="app_login")
     */
    public function requestLoginLink(ManagerRegistry $doctrine, MailerInterface $mailer, LoginLinkHandlerInterface $loginLinkHandler, UserRepository $userRepository)
    {
        // check if login form is submitted
        if (isset($_GET['email'])) {
            // load the user in some way (e.g. using the form input)
            
            $email = htmlspecialchars($_GET['email']);
            $email = stripslashes($email);
            $email = trim($email);

            $user = $userRepository->findOneBy(['email' => $email]);

            // If the email matches with an account
            if ($user) {
            
                // Create a login link for $user this returns an instance
                // Of LoginLinkDetails
                $loginLinkDetails = $loginLinkHandler->createLoginLink($user);

                // Generate Random code
                $randomCode = rand(100000, 999999);

                // Add code to user's code in DB
                $entityManager = $doctrine->getManager();
                $user->setConnectlink($loginLinkDetails);
                $user->setConnectcode($randomCode);
                $entityManager->flush();


                // Generate mail with code & link to connect (The user is also automatically redirect)
                $mail = (new Email())
                    ->from("no-reply@yourdomain.com")
                    ->to($user->getEmail())
                    ->subject("[".$randomCode."] Votre code de connexion")
                    ->html('<h1>Contenu</h1><p>Code:'.$randomCode.'</p> <p>Lien :'.$this->generateUrl('app_code_connexion', ['email'=>$user->getEmail()], UrlGenerator::ABSOLUTE_URL).'</p>');

                    $mailer->send($mail);
                
                // Redirect to route to check password with email in GET method
                return $this->redirectToRoute('app_code_connexion', ['email' => $user->getEmail()]);
            } else {
                
            }
        }

        // if it's not submitted, render the "login" form
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/code-connexion/{email}", name="app_code_connexion")
     */
    public function verifyCodeConnexion(ManagerRegistry $doctrine, string $email) {
        if ($_POST) {
            $user = $doctrine->getRepository(User::class)->findOneBy(['email' => $_POST['email']]);

            // Check if email is attached to an account in DB
            if ($user) {
                // Check if connect code is right
                if (($user->getConnectcode()) === $_POST['code']) {
                    $this->addFlash(
                        'success',
                        'Vous êtes maintenant connecté'
                    );
                    // If everythings is ok : redirect to auto loggin link
                    return $this->redirect($user->getConnectlink());
                }
            }
        }
        return $this->render('security/code_connexion.html.twig', [
            'email' => $email
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function check()
    {
        throw new \LogicException('This code should never be reached');
    }

}
