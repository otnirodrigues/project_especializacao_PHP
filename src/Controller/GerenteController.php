<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Agencia;
use App\Entity\Gerente;
use App\Form\GerenteType;
use App\Repository\ContaRepository;
use App\Repository\AgenciaRepository;
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

    //Buscando contas da agencia
    #[Route('/gerente/agencia/contas', name: 'app_contas_agencia_listar', methods: ['GET'])]
    public function listarContasAgencias(User $user, Agencia $agencia, AgenciaRepository $agenciaRepository, ContaRepository $contaRepository): Response
    {
        $minhasContas = $agenciaRepository->findBy(['contas' => $agencia->getId() ]);
            return $this->render('cliente/index.html.twig', [
            'agencia' => $agencia,
            'contas'=> $minhasContas,
        ]);
    }
    
}
