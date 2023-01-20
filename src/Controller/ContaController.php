<?php

namespace App\Controller;

use App\Entity\Conta;
use App\Form\ContaType;
use App\Entity\Transacao;
use App\Repository\ContaRepository;
use App\Form\TransacaoDepositarType;
use App\Repository\AgenciaRepository;
use App\Repository\TransacaoRepository;
use App\Form\TransferenciaTransacaoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            
        
            if($valor <= 0){
                $this->addFlash('error', 'Valor de deposito Inválido!');
                return $this->redirectToRoute('app_home_page');

            }else{
                $deposito->setDescricao('Deposito');
                $deposito->setData(new \DateTime());
                $conta->setSaldo($conta->getSaldo() + $valor);
                $entityManager->persist($conta);
                $entityManager->flush();
                $this->addFlash('success', 'Deposito realizado com sucesso!');
                $transacaoRepository->save($deposito, true);
                return $this->redirectToRoute('app_home_page');
            }
        }
        

        return $this->renderForm('conta/deposito.html.twig', 
            [ 'form' => $form ]);

    
    }

    #[Route('/sacar', name: 'app_sacar')]
    public function sacar(Request $request, ContaRepository $contaRepository, TransacaoRepository $transacaoRepository, AgenciaRepository $agenciaRepository,  EntityManagerInterface $entityManager): Response
    {
        $saque = new Transacao();
        $form = $this->createForm(TransacaoDepositarType::class, $saque);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $contaSaque = $saque->getContaDestino()->getNumero();
            $valor = $saque->getValor();

            $conta = $contaRepository->findOneBy(['numero' => $contaSaque]);
            if($conta->getSaldo() < $valor){
                $this->addFlash('error', 'Saldo Insuficiente!');
                return $this->redirectToRoute('app_home_page');
            }
            if($valor <= 0){
                $this->addFlash('error', 'Digite um valor válido para sacar!');
                return $this->redirectToRoute('app_home_page');
            }else{
                $saque->setDescricao('Saque');
                $saque->setData(new \DateTime());
                $conta->setSaldo($conta->getSaldo() - $valor);
                $entityManager->persist($conta);
                $entityManager->flush();
                $this->addFlash('success', 'Saque realizado com sucesso!');
                $transacaoRepository->save($saque, true);
                return $this->redirectToRoute('app_home_page');
            }
        }

        return $this->renderForm('conta/saque.html.twig', 
            [ 'form' => $form ]);

    
    }

    #[Route('/transferir', name: 'app_tranferir')]
    public function tranferir(Request $request, ContaRepository $contaRepository, TransacaoRepository $transacaoRepository, AgenciaRepository $agenciaRepository,  EntityManagerInterface $entityManager): Response
    {
        $transferencia = new Transacao();
        $form = $this->createForm(TransferenciaTransacaoType::class, $transferencia);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $contaDestino = $transferencia->getContaDestino()->getNumero();
            $contaEnvio = $transferencia->getContaRemetente()->getNumero();
            $valor = $transferencia->getValor();

            $contaRemetente = $contaRepository->findOneBy(['numero' => $contaEnvio]);
            $contaRecebimento = $contaRepository->findOneBy(['numero' => $contaDestino]);
            if($contaRemetente === $contaRecebimento){
                $this->addFlash('error', 'Não se pode realizar transferencia para contas iguais!');
                return $this->redirectToRoute('app_home_page');
            }
         
            $transferencia->setDescricao('Transferencia');
            $transferencia->setData(new \DateTime());
            if($contaRemetente->getSaldo() < $valor){
                $this->addFlash('error', 'Saldo Insuficiente!');
                return $this->redirectToRoute('app_home_page');
            }
            if($valor <= 0){
                $this->addFlash('error', 'Digite um valor valido para transferencia!');
                return $this->redirectToRoute('app_home_page');

            }else{
                $contaRecebimento->setSaldo($contaRecebimento->getSaldo() + $valor);
                $entityManager->persist($contaRecebimento);
                $entityManager->flush();
                $contaRemetente->setSaldo($contaRemetente->getSaldo() - $valor);
                $entityManager->persist($contaRemetente);
                $entityManager->flush();
                $this->addFlash('success', 'Transferencia realizado com sucesso!');
                $transacaoRepository->save($transferencia, true);
                return $this->redirectToRoute('app_home_page');

            }
            
        }

        return $this->renderForm('conta/transferencia.html.twig', 
            [ 'form' => $form ]);

    
    }


    
}
