<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Conta;
use App\Form\ContaType;
use App\Entity\Transacao;
use App\Form\TransacaoType;
use App\Form\TransacaoSaqueType;
use App\Repository\ContaRepository;
use App\Repository\TransacaoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cliente')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ClienteController extends AbstractController
{


    //Listar Contas Clientes
    #[Route('/cliente/{id}', name: 'app_cliente_listar', methods: ['GET'])]
    public function listarContasClientes(User $user, ContaRepository $contaRepository): Response
    {
        $minhasContas = $contaRepository->findBy(['user' => $user->getId() ]);
            return $this->render('cliente/index.html.twig', [
            'user' => $user,
            'contas'=> $minhasContas,
        ]);
    }

    //Acessar uma conta
    #[Route('/cliente/{id}/conta/{conta}', name: 'app_cliente_verConta', methods: ['GET'])]
    public function verConta(User $user,TransacaoRepository $transacaoRepository, ContaRepository $contaRepository, $conta): Response
    {
        $conta = $contaRepository->findOneBy(['user' => $user->getId(), 'id' => $conta]);
        $listaTransacoes = $transacaoRepository->findByListarContas($conta);
        return $this->render('conta/show.html.twig', [
            'user' => $user,
            'conta'=> $conta,
            'listaTransacoes'=> $listaTransacoes,
        ]);
    }

    // Criar nova conta Usuário logado
    #[Route('/cliente/{id}/conta/add', name: 'app_cliente_nova_conta', methods: ['GET', 'POST'])]
    public function criandoConta(Request $request, User $user, ContaRepository $contaRepository): Response
    {
        $conta = new Conta();
        $form = $this->createForm(ContaType::class, $conta);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $conta->setNumero(rand(111, 999));
            $conta->setUser($this->getUser());
            $conta->setSaldo(0);
            $conta->setIsActive(false);
            $contaRepository->save($conta, true);
            $this->addFlash('success', 'Conta criada com sucesso!');
            return $this->redirectToRoute('app_cliente_listar', ['id'=> $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('conta/add.html.twig', [
            'conta' => $conta,
            'form' => $form,
        ]);
    }


    // Saque com o usuário logado

    #[Route('/cliente/{id}/{conta}/sacar', name: 'app_cliente_saque', methods: ['GET', 'POST'])]
    public function sacarCliente(Request $request,$conta, TransacaoRepository $transacaoRepository,ContaRepository $contaRepository, 
    EntityManagerInterface $entityManager, User $user): Response
    {
        $minhaConta = $contaRepository->findOneBy(['user' => $user->getId(), 'id' => $conta]);
        $transacao = new Transacao();
        $form = $this->createForm(TransacaoSaqueType::class, $transacao);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $transacao->setDescricao('Saque');
            $transacao->setData(new \DateTime());
            $transacao->setContaDestino($minhaConta);
            $valor = $transacao->getValor();
            if($minhaConta->getSaldo() < $valor ){
                $this->addFlash('error', 'Saldo Insuficiente!');
            }
            if($valor <= 0 ){
                $this->addFlash('error', 'Digite um valor válido para sacar!');
            }
            $minhaConta->setSaldo($minhaConta->getSaldo() - $valor);
            $entityManager->persist($minhaConta);
            $entityManager->flush();
            $this->addFlash('success', 'Saque realizado com sucesso!');
            $transacaoRepository->save($transacao, true);

            return $this->redirectToRoute('app_cliente_verConta', ['id'=> $user->getId(), 'conta'=> $minhaConta->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cliente/transacao.html.twig', [
            'transacao' => $transacao,
            'form' => $form,
        ]);
    }

    // Deposito com o usuário logado
    #[Route('/cliente/{id}/{conta}/deposito', name: 'app_cliente_deposito', methods: ['GET', 'POST'])]
    public function depositarCliente(Request $request,$conta, TransacaoRepository $transacaoRepository,ContaRepository $contaRepository, 
    EntityManagerInterface $entityManager, User $user): Response
    {
        $minhaConta = $contaRepository->findOneBy(['user' => $user->getId(), 'id' => $conta]);
        $transacao = new Transacao();
        $form = $this->createForm(TransacaoSaqueType::class, $transacao);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $transacao->setDescricao('Deposito');
            $transacao->setData(new \DateTime());
            $transacao->setContaDestino($minhaConta);
            $valor = $transacao->getValor();
            
            if($valor <= 0 ){
                $this->addFlash('error', 'Digite um valor válido para sacar!');
            }
            $minhaConta->setSaldo($minhaConta->getSaldo() + $valor);
            $entityManager->persist($minhaConta);
            $entityManager->flush();
            $this->addFlash('success', 'Deposito realizado com sucesso!');
            $transacaoRepository->save($transacao, true);

            return $this->redirectToRoute('app_cliente_verConta', ['id'=> $user->getId(), 'conta'=> $minhaConta->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cliente/transacao.html.twig', [
            'transacao' => $transacao,
            'form' => $form,
        ]);
    }
    
    #[Route('/cliente/{id}/{conta}/transferencia', name: 'app_cliente_transferencia', methods: ['GET', 'POST'])]
    public function tranferenciaCliente(Request $request,$conta, TransacaoRepository $transacaoRepository,ContaRepository $contaRepository, 
    EntityManagerInterface $entityManager, User $user): Response
    {
        $minhaConta = $contaRepository->findOneBy(['user' => $user->getId(), 'id' => $conta]);
        $transacao = new Transacao();
        $form = $this->createForm(TransacaoType::class, $transacao);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $transacao->setDescricao('Transferencia');
            $transacao->setData(new \DateTime());
            $transacao->setContaRemetente($minhaConta);
            $valor = $transacao->getValor();

            if($minhaConta->getSaldo() < $valor){
                $this->addFlash('error', 'Saldo Insuficiente!');
            }

            if($valor <= 0){
                $this->addFlash('error', 'Digite um valor valido para transferencia!');
            }

            $minhaConta->setSaldo($minhaConta->getSaldo() - $valor);
            $entityManager->persist($minhaConta);
            $numeroDados = $transacao->getContaDestino();

            if($minhaConta === $numeroDados){
                $this->addFlash('error', 'Não se pode realizar transferencia para contas iguais!');
            }
            $numeroDaConta = $numeroDados->getNumero();
            $valor = $transacao->getValor();
            $conta = $contaRepository->findOneBy(['numero' => $numeroDaConta]);
            $conta->setSaldo($conta->getSaldo() + $valor);
            $entityManager->persist($conta);
            $entityManager->flush();
            $this->addFlash('success', 'Transferencia realizado com sucesso!');
            $transacaoRepository->save($transacao, true);

            return $this->redirectToRoute('app_cliente_verConta', ['id'=> $user->getId(), 'conta'=> $minhaConta->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cliente/transferencia.html.twig', [
            'transacao' => $transacao,
            'form' => $form,
        ]);
    }

    #[Route('/cliente/{id}/conta/{conta}/encerrar', name: 'app_conta_encerrar')]
    public function encerrar($conta, User $user, TransacaoRepository $transacaoRepository, ContaRepository $contaRepository, EntityManagerInterface $entityManager):Response
    {

        $minhaConta = $contaRepository->findOneBy(['user' => $user->getId(), 'id' => $conta]);
           
            
            $saldo = $minhaConta->getSaldo();
            if ($saldo > 0) {
                $this->addFlash('error', 'A conta só pode ser encerrada se não existir saldo!');
                return $this->redirectToRoute('app_cliente_verConta', ['id' => $user->getId(),'conta'=> $minhaConta->getId()]);
            }
            $transaocoes = $transacaoRepository->findBy(['contaRemetente' => $minhaConta->getId()]);
            foreach ($transaocoes as $transacao) {
                $transacao->setContaRemetente(null);
                $entityManager->persist($transacao);
            }
            $contaRepository->remove($minhaConta);   
            $entityManager->flush();
            $this->addFlash('success', 'Conta encerrada com sucesso!');
            return $this->redirectToRoute('app_cliente_listar', ['id' => $user->getId()]);
    
    }
}