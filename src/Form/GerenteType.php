<?php

namespace App\Form;

use App\Entity\Gerente;
use App\Repository\AgenciaRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class GerenteType extends AbstractType
{
    private $users;
    private $agencia;

    public function __construct(ManagerRegistry $registro)
    {
        $this->users = new UserRepository($registro);
        $this->agencia = new AgenciaRepository($registro);
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $users = $this->users->findAll();
        $agencia= $this->agencia->findAll();
        
        $builder
            ->add('nome')
            // ->add('agencia', ChoiceType::class,[
            //         'choices' => $agencia,
            //         'choice_label' => 'nome',
            //         'placeholder' => 'Selecione uma agencia',])

            // ->add('user', ChoiceType::class,[
            //     'choices' => $users,
            //     'choice_label' => 'nome',
            //     'placeholder' => 'Selecione um Usuário como Gerente',])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gerente::class,
        ]);
    }
}
