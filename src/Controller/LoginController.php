<?php

namespace App\Controller;

use App\Entity\User;
use App\EntityListener\UserListener;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class LoginController extends AbstractController
{
 

    #[Route('/', name: 'app_login', methods:['GET'])]
    public function index( ): Response
    {
        return $this->render('login/index.html.twig');
    }

    #[Route('/logout', name: 'app_logout', methods:['GET'])]
    public function logout( Session $session  ): Response
    {
        $session->clear();
        return $this->redirectToRoute('app_login');
    }

    #[Route('/', name: 'app_login_check', methods:['POST'])]
    public function check_login( Request $request, UserRepository $userRepository, Session $session )
    {
        $username = $request->request->get('email');
        $password = $request->request->get('password');
        
        $user = $userRepository->findOneBy(['email' => $username]);

        if (!$user) {
            // L'utilisateur n'existe pas
            return $this->render('login/index.html.twig', [
                'error' => 'Nom d\'utilisateur ou mot de passe incorrect'
            ]);
        }
       // check password here
       if (!password_verify($password, $user->getPassword())) {
            return $this->render('login/index.html.twig', [
                'error' => 'Nom d\'utilisateur ou mot de passe incorrect'
            ]);
        }

        // Redirige vers la page d'accueil
        $session->set('user_id', $user->getId());
        $session->set('user_email', $user->getEmail());
        $session->set('user_type', $user->getTypeUser());
        $session->set('user_firstname', $user->getFirstname());
        $session->set('user_lastname', $user->getLastname());
        
        return $this->redirectToRoute('app_home');
    }

}
