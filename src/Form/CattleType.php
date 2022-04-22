<?php

namespace App\Form;

use App\Entity\Cattle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CattleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', IntegerType::class, ['label'=>'Código'])
            ->add('milk', NumberType::class, ['label'=>'Leite por semana (L)'])
            ->add('ration', NumberType::class, ['label'=>'Ração por semana (Kg)'])
            ->add('weight', NumberType::class, ['label'=>'Peso (Kg)'])
            ->add('birth', DateType::class, ['label'=>'Nascimento'])
            ->add('Salvar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cattle::class,
        ]);
    }
}
