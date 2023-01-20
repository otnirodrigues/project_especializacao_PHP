<?php

namespace App\Form;

use App\Entity\Conta;
use App\Repository\AgenciaRepository;
use App\Repository\TipoContaRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContaType extends AbstractType
{
    private $tipo_conta;
    private $agencias;
    private $registro;

    public function __construct(ManagerRegistry $registro)
    {
        $this->registro= $registro;
        $this->agencias = new AgenciaRepository($registro);
        $this->tipo_conta = new TipoContaRepository ($registro);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $tipo_conta = $this->tipo_conta->findAll();
        $agencias = $this->agencias->findAll();
        
        $builder
            ->add('tipoConta', ChoiceType::class,[
                'choices' => $tipo_conta,
                'choice_label' => 'tipo',
                'placeholder' => 'Selecione um tipo de conta',
            ])

            ->add('agencia', ChoiceType::class,[
                'choices' => $agencias,
                'choice_label' => 'nome',
                'placeholder' => 'Selecione uma agência',
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conta::class,
        ]);
    }
}
