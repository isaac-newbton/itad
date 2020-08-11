<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\PasswordReset;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('dashboard');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', ['bodyClass'=>'login_page', 'last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/reset-password", name="reset_password")
     */
    public function resetPassword(Request $request, PasswordReset $passwordResetService, UserRepository $userRepository){
        if($request->isMethod('post')){
            $email = $request->get('email');
            $user = $userRepository->findOneBy(['email'=>$email]);
            if($user){
                $password = $passwordResetService->resetUserPassword($user);
                $mailed = mail(
                    $email,
                    'Password Reset',
                    "Someone requested a password reset for this email ($email). Use the updated password below to log in and immediately visit your user profile (" . $this->generateUrl('user_profile', [], UrlGeneratorInterface::ABSOLUTE_URL) . ") to change it to a more secure password. Your temporary password is: $password",
                    'From: noreply@hanlon-itad.isaacnewbton.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion()
                );
            }
            return $this->render('forgot_password.html.twig', ['bodyClass'=>'forgot_password', 'email'=>$email, 'mailed'=>$mailed ?? 'N/A', 'password'=>$password ?? 'N/A']);
        }
        return $this->render('forgot_password.html.twig', ['bodyClass'=>'forgot_password', 'email'=>false, 'mailed'=>'N/A']);
    }
}
