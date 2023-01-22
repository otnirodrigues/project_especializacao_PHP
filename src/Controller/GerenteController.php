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

    
}
