<?php

namespace App\Controller;


use App\Entity\Transacao;
use App\Repository\UserRepository;
use App\Repository\ContaRepository;
use App\Form\TransacaoDepositarType;
use App\Repository\AgenciaRepository;
use App\Repository\GerenteRepository;
use App\Repository\TransacaoRepository;
use App\Form\TransferenciaTransacaoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/gerente')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class GerenteController extends AbstractController
{
    
    //Buscar Agencia Do gerente
    #[Route('/agencia', name: 'app_gerente_agencia_listar', methods: ['GET'])]
    public function listarAgenciasGerente(AgenciaRepository $agenciaRepository, GerenteRepository $gerenteRepository, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $gerente = $gerenteRepository->findOneBy(['user' => $user]);
        $minhasAgencia = $agenciaRepository->findOneBy(['gerente' => $gerente->getId() ]);
            return $this->render('gerente/index.html.twig', [
            
            'minhaAgencia'=> $minhasAgencia,
        ]);
    }    

    //Listar Todas as contas Agencia
    #[Route('/agencia/conta', name: 'app_gerente_agencia_conta_listar', methods: ['GET'])]
    public function listarContaAgenciasGerente(TransacaoRepository $transacaoRepository, AgenciaRepository $agenciaRepository, GerenteRepository $gerenteRepository, ContaRepository $contaRepository, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $gerente = $gerenteRepository->findOneBy(['user' => $user]);
        $minhaAgencia = $agenciaRepository->findOneBy(['gerente' => $gerente->getId() ]);
        $contas = $contaRepository->findAll();
        $transacao = $transacaoRepository->findAll();
            return $this->render('gerente/listarContas.html.twig', [
            
            'transacao' => $transacao,
            'contas'=> $contas,
        ]);
    }    

    
    //Depositar por Gerente

    #[Route('/depositar', name: 'app_gerente_depositar')]
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
                return $this->redirectToRoute('app_gerente_agencia_conta_listar');

            }else{
                $deposito->setDescricao('Deposito');
                $deposito->setData(new \DateTime());
                $conta->setSaldo($conta->getSaldo() + $valor);
                $entityManager->persist($conta);
                $entityManager->flush();
                $this->addFlash('success', 'Deposito realizado com sucesso!');
                $transacaoRepository->save($deposito, true);
                return $this->redirectToRoute('app_gerente_agencia_conta_listar');
            }
        }

        return $this->renderForm('gerente/deposito.html.twig', 
            [ 'form' => $form ]);

    }

    //Saque por Gerente

    #[Route('/sacar', name: 'app_gerente_sacar')]
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
                return $this->redirectToRoute('app_gerente_agencia_conta_listar');
            }
            if($valor <= 0){
                $this->addFlash('error', 'Digite um valor válido para sacar!');
                return $this->redirectToRoute('app_gerente_agencia_conta_listar');
            }else{
                $saque->setDescricao('Saque');
                $saque->setData(new \DateTime());
                $conta->setSaldo($conta->getSaldo() - $valor);
                $entityManager->persist($conta);
                $entityManager->flush();
                $this->addFlash('success', 'Saque realizado com sucesso!');
                $transacaoRepository->save($saque, true);
                return $this->redirectToRoute('app_gerente_agencia_conta_listar');
            }
        }

        return $this->renderForm('gerente/saque.html.twig', 
            [ 'form' => $form ]);

    
    }

    //Transferir com Gerente
    #[Route('/transferir', name: 'app_gerente_tranferir')]
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
                return $this->redirectToRoute('app_gerente_agencia_conta_listar');
            }
         
            $transferencia->setDescricao('Transferencia');
            $transferencia->setData(new \DateTime());
            if($contaRemetente->getSaldo() < $valor){
                $this->addFlash('error', 'Saldo Insuficiente!');
                return $this->redirectToRoute('app_gerente_agencia_conta_listar');
            }
            if($valor <= 0){
                $this->addFlash('error', 'Digite um valor valido para transferencia!');
                return $this->redirectToRoute('app_gerente_agencia_conta_listar');

            }else{
                $contaRecebimento->setSaldo($contaRecebimento->getSaldo() + $valor);
                $entityManager->persist($contaRecebimento);
                $entityManager->flush();
                $contaRemetente->setSaldo($contaRemetente->getSaldo() - $valor);
                $entityManager->persist($contaRemetente);
                $entityManager->flush();
                $this->addFlash('success', 'Transferencia realizado com sucesso!');
                $transacaoRepository->save($transferencia, true);
                return $this->redirectToRoute('app_gerente_agencia_conta_listar');

            }
            
        }

        return $this->renderForm('gerente/transferencia.html.twig', 
            [ 'form' => $form ]);

    
    }
}
