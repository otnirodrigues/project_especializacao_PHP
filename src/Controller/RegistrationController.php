<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Conta;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Repository\ContaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserRepository $userRepository, ContaRepository $contaRepository ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ROLE_CLIENT']);

            $entityManager->persist($user);

            $email = $form->get('email')->getData();

            $userExiste = $userRepository->findOneBy(['email' => $email]);
            
            if ($userExiste){
                $user = $userExiste;
                $contaExistente = $contaRepository->findOneBy(['usuario' => $userExiste->getId(),'active' => true]);
                
            }else{
                $contaExistente = false;
            }

            if ($contaExistente){
                return $this->redirectToRoute('app_conta_criar', ['user' => $userExiste->getId()]);
            }
           
            $entityManager->persist($user);
            

            $agencia = $form->get('conta')->getData();
        
            $conta = new Conta();
            if ($userExiste){
                $conta->setUser($userExiste);
            }else{
                $conta->setUser($user);
            }
            
            $conta->setSaldo(0);
            $conta->setNumero(rand(111, 999));
            $conta->setAgencia($agencia->getAgencia());
            $conta->setTipoConta($agencia->getTipoConta());

          

            
            $conta->setIsActive(false);
        
            $entityManager->persist($conta);
            $entityManager->flush();


            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
