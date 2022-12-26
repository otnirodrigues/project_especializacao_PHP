<?php

namespace App\Controller;

use App\Repository\AgenciaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgenciaController extends AbstractController
{
    #[Route('/agencia', name: 'app_agencia')]
    public function index(AgenciaRepository $agencias): Response
    {
    
        return $this->render('agencia/index.html.twig', [
            'controller_name' => 'AgenciaController',
            'agencias' => $agencias->findAll()
        ]);
    }
}
