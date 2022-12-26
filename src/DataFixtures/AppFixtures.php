<?php

namespace App\DataFixtures;

use App\Entity\Agencia;
use App\Entity\Banco;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        
        //Criando o Banco no db
        $Banco = new Banco();
        $Banco->setNome('Banco Recifense');
        $manager->persist($Banco);

        //criando Agencias no db
        for ($i = 0; $i < 5; $i++){
            $Agencia = new  Agencia();
            $Agencia->setNome('Agencia' . $i);
            $Agencia->setRua('Rua Projetada');
            $Agencia->setBairro('Centro');
            $Agencia->setNumero(01);
            $Agencia->setCidade('Recife');
            $Agencia->setUf('PE');
            $Agencia->setCep(11111-111);
            $Agencia->setBanco($Banco);
            $manager->persist($Agencia);
        }

        $manager->flush();
    }
}
