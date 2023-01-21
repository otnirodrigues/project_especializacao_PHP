<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home_page');
        }
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(){
    
    }

}

    
//     #[Route('/login_success', name: 'login_success')]
//     public function postLoginRedirectAction(UserRepository $userRepository)
//     {
//         $user_loged = $this->getUser();
//         if ($user_loged){
//             $user = $userRepository->findOneBy(
    
//                 ['email' => $user_loged->getUserIdentifier()]
            
//             );
    
//             if (in_array('ROLE_ADMIN', $user->getRoles() ) ) {
//                 print_r($user->getRoles());
//                 return $this->redirectToRoute('app_admin');
//                 }
//                 if (in_array('ROLE_CLIENT', $user->getRoles())) {
//                     print_r($user->getRoles());
        
//                     return $this->redirectToRoute('app_cliente', ['id' => $user->getId()]);
//                 } 
//                 if (in_array('ROLE_GERENTE', $user->getRoles())) {
//                     print_r($user->getRoles());        
//                     return $this->redirectToRoute('app_gerente', ['gerente' => $user->getId()]);
//                 }
    
//            }
//     }
// }