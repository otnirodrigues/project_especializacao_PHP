<?php

namespace App\Controller;

use App\Entity\Agencia;
use App\Entity\Gerente;
use App\Form\AgenciaType;
use App\Form\GerenteType;
use App\Repository\BancoRepository;
use App\Repository\ContaRepository;
use App\Repository\AgenciaRepository;
use App\Repository\GerenteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    # Listar Agencias Painel Admim 
    #[Route('/admin/agencia', name: 'app_agencia_admin', priority: 2)]
    public function listarAgencias(AgenciaRepository $agencias): Response{
    
        return $this->render('admin/listarAgencia.html.twig', [
            'controller_name' => 'AgenciaController',
            'agencias' => $agencias->findAll()
        ]);
    }

    # Listar Contas Painel Admim
    #[Route('admin/conta', name: 'app_conta_admin')]
    public function listarContas(ContaRepository $contas): Response
    {
        return $this->render('admin/listarContas.html.twig', [
            'controller_name' => 'ContaController',
            'contas' => $contas->findAll()
        ]);
    }

    # Listar Gerentes Painel Admim
    #[Route('/admin/gerente', name: 'app_gerente_admin')]
    public function listarGerente(GerenteRepository $gerente): Response
    {
        return $this->render('gerente/index.html.twig', [
            'controller_name' => 'GerenteController',
            'gerentes' => $gerente->findAll()
        ]);
    }

    # Criar Gerentes Painel Admim
    #[Route('admin/gerente/add', name: 'app__admin_gerente_add')]
    public function addGerente(Request $request, GerenteRepository $gerente) : Response {
      
        $form = $this->createForm(GerenteType::class, new Gerente());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $gerentes = $form->getData();
            $gerente->save($gerentes, true);
            $this->addFlash('success', 'Gerente Criado');
            return $this->redirectToRoute('app_gerente_admin');
       }

    return $this->renderForm(
        'admin/addGerente.html.twig',
        [ 'form' => $form ]
    );
    }

    # Criar Agencias Painel Admim
    #[Route('/admin/agencia/add', name: 'app_admin_agencia_add')]
    public function addAgencias(Request $request, AgenciaRepository $agencia) : Response {
        
        $agenciaNew = new Agencia();
        $form = $this->createForm(AgenciaType::class, $agenciaNew);

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $agencias = $form->getData();
            $agencia->save($agencias, true);
            $this->addFlash('success', 'Agencia criada');
            return $this->redirectToRoute('app_agencia_admin');
       }

    return $this->renderForm(
        'admin/addAgencia.html.twig',
        [ 'form' => $form ]
    );
    }
}