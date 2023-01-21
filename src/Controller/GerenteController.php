<?php

namespace App\Controller;

use App\Entity\Gerente;
use App\Form\GerenteType;
use App\Repository\GerenteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GerenteController extends AbstractController
{
    #[Route('/gerente', name: 'app_gerente')]
    public function index(): Response
    {
        return $this->render('gerente/index.html.twig', [
            'controller_name' => 'GerenteController',
        ]);
    }

    #[Route('/gerente/add', name: 'app_gerente_add', priority: 2)]
    public function add(Request $request, GerenteRepository $gerente) : Response {
      
        $form = $this->createForm(GerenteType::class, new Gerente());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $gerentes = $form->getData();
            $gerente->save($gerentes, true);
            $this->addFlash('success', 'Gerente Criado');
            return $this->redirectToRoute('app_gerente');
       }

    return $this->renderForm(
        'gerente/add.html.twig',
        [ 'form' => $form ]
    );
    }
}
