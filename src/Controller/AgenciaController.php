<?php

namespace App\Controller;

use App\Entity\Agencia;
use App\Form\AgenciaType;
use App\Repository\AgenciaRepository;
use App\Repository\BancoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgenciaController extends AbstractController
{
    #[Route('/agencia', name: 'app_agencia')]
    public function index(AgenciaRepository $agencias): Response{
    
        return $this->render('agencia/index.html.twig', [
            'controller_name' => 'AgenciaController',
            'agencias' => $agencias->findAll()
        ]);
    }

    #[Route('/agencia/{id}', name: 'app_agencia_show')]
    public function showOne(Agencia $agenciaID): Response{
        return $this->render('agencia/show.html.twig', [
            'agencias' => $agenciaID
        ]);
    }

    #[Route('/agencia/add', name: 'app_agencia_add', priority: 2)]
    public function add(Request $request, AgenciaRepository $agencia, BancoRepository $banco) : Response {
        
        $agenciaNew = new Agencia();
        $form = $this->createForm(AgenciaType::class, $agenciaNew);

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $agencias = $form->getData();
            $agencia->save($agencias, true);
            $this->addFlash('success', 'Agencia criada');
            return $this->redirectToRoute('app_agencia');
       }

    return $this->renderForm(
        'agencia/add.html.twig',
        [ 'form' => $form ]
    );
       
    }

}