<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/security", name="security")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SecurityController.php',
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/resetpws", name="resetpws")
     */
    public function resetpws(Request $request)
    {
        $email = $request->request->get('spanEmailrecover');

        $this->auth->sendPasswordResetLink($email, null, 'es');

        return $this->redirectToRoute('user_index');
    }


    /**
     * @Route ("/setting", name="setting" )
     * @param Request $request
     * @return Response
     */
    public function showSettings(Request $request){

        return $this->render('security/setting.html.twig',
            [
                'ClientID' => $_ENV['QUICKBOOK_CLIENTID'],
                'ClientSecret' => $_ENV['QUICKBOOK_SECRET'],
                'RedirectURI' => $_ENV['QUICKBOOK_REDIRECT'],
                'scope' => "com.intuit.quickbooks.accounting openid profile email phone address",
                'baseUrl' => $_ENV['QUICKBOOK_ENVIROMENT']
            ] );
    }



}
