<?php

namespace App\Form;

use App\Entity\Transacao;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransacaoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('descricao')
            ->add('valor')
            ->add('data')
            ->add('trasacaoContas')
            ->add('contaDestino')
            ->add('contaRemetente')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transacao::class,
        ]);
    }
}
