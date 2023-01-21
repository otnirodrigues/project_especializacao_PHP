<?php

namespace App\Form;

use App\Entity\Agencia;
use App\Repository\GerenteRepository;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AgenciaType extends AbstractType
{
    private $gerentes;

    public function __construct(ManagerRegistry $registro)
    {
        $this->gerentes = new GerenteRepository($registro);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $gerentes = $this->gerentes->findAll();

        $builder
            ->add('nome')
            ->add('rua')
            ->add('bairro')
            ->add('numero')
            ->add('cidade')
            ->add('uf')
            ->add('cep')
            ->add('gerente', ChoiceType::class,[
                'choices' => $gerentes,
                'choice_label' => 'nome',
                'placeholder' => 'Selecione um Gerente',])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agencia::class,
        ]);
    }
}
