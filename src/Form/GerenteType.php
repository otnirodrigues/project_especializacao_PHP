<?php

namespace App\Form;

use App\Entity\Gerente;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class GerenteType extends AbstractType
{
    private $users;

    public function __construct(ManagerRegistry $registro)
    {
        $this->users = new UserRepository($registro);
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $users = $this->users->findAll();
        
        $builder
            ->add('nome')
            ->add('user', ChoiceType::class,[
                'choices' => $users,
                'choice_label' => 'nome',
                'placeholder' => 'Selecione um Usuário como Gerente',])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gerente::class,
        ]);
    }
}
