<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Banco;
use App\Entity\Agencia;
use App\Entity\TipoConta;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {}
    
    public function load(ObjectManager $manager): void
    {

        // $product = new Product();
        // $manager->persist($product);
        
        //Criando User
        $user1 = new User();
        $user1 -> setEmail('admin@bank.com');
        $user1 -> setPassword($this->hasher->hashPassword($user1, '505887'));
        $manager->persist($user1);

        //Criando o Banco no db
        $Banco = new Banco();
        $Banco->setNome('Banco Recifense');
        $manager->persist($Banco);

        //criando Agencias no db
        for ($i = 1; $i < 6; $i++){
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

        #criando TipoConta

        $poupanca = new TipoConta();
        $poupanca -> setTipo('Poupança');
        $manager->persist($poupanca);
        
        $corrente = new TipoConta();
        $corrente -> setTipo('Corrente');
        $manager->persist($corrente);

        $manager->flush();
    }
}
