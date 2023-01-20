<?php

namespace App\Controller;

use App\Entity\Conta;
use App\Form\ContaType;
use App\Entity\Transacao;
use App\Form\TransacaoDepositarType;
use App\Repository\ContaRepository;
use App\Repository\AgenciaRepository;
use App\Repository\TransacaoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Date;

class ContaController extends AbstractController
{
    #[Route('/conta', name: 'app_conta')]
    public function index(ContaRepository $contas): Response
    {
        return $this->render('conta/index.html.twig', [
            'controller_name' => 'ContaController',
            'contas' => $contas->findAll()
        ]);
    }

    #[Route('/depositar', name: 'app_depositar')]
    public function depositar(Request $request, ContaRepository $contaRepository, TransacaoRepository $transacaoRepository, AgenciaRepository $agenciaRepository,  EntityManagerInterface $entityManager): Response
    {
        $deposito = new Transacao();
        $form = $this->createForm(TransacaoDepositarType::class, $deposito);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $contaDestino = $deposito->getContaDestino()->getNumero();
            $valor = $deposito->getValor();

            $conta = $contaRepository->findOneBy(['numero' => $contaDestino]);
         
            $deposito->setDescricao('Deposito');
            $deposito->setData(new \DateTime());
            $conta->setSaldo($conta->getSaldo() + $valor);
            $entityManager->persist($conta);
            $entityManager->flush();
            $this->addFlash('success', 'Deposito realizado com sucesso!');
            $transacaoRepository->save($deposito, true);
            return $this->redirectToRoute('app_home_page');
        }

        return $this->renderForm('conta/deposito.html.twig', 
            [ 'form' => $form ]);

    
    }

    #[Route('/conta/add', name: 'app_conta_add', priority: 2)]
    public function add(Request $request, ContaRepository $conta) : Response {
      
        $formConta = $this->createForm(ContaType::class, new Conta());

        $formConta->handleRequest($request);
        if ($formConta->isSubmitted() && $formConta->isValid()){
            $contas = $formConta->getData();
            $conta->save($contas, true);
            $this->addFlash('success', 'Conta criada');
            return $this->redirectToRoute('app_conta');
       }

    return $this->renderForm(
        'conta/add.html.twig',
        [ 'formConta' => $formConta ]
    );
       
    }
}
